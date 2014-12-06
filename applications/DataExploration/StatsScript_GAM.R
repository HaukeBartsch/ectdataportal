################################################################
#
## GAM's
#

library(mgcv)

# read json files with rjson, install with: R CMD INSTALL rjson_0.2.11.tar
library("rjson")

#
# In order to run this model use the following command line for R:
#   /usr/bin/R --no-restore --no-save -q -f StatsScript_GAM.R --args project_name="Project01" user_name="ME" cookie="42" vertexwise=1 request=0
# 
# Make sure that R is using the current directory as working directory. Otherwise files that are sourced
# cannot be found.

# command line arguments
cmd_args = commandArgs();
argsfound = 0;
for (arg in cmd_args) {
  if (arg == "--args") {
     argsfound = 1;
  }
  if (arg != "--args" && argsfound == 1) {
      eval(parse(text=arg)); 
  }
}

if (!exists("project_name")) {
  project_name = "Project01";
  user_name    = "dataportal";
  cookie       = "42";
  vertexwise   = 0;
  version      = "";
  vertexShowSubjects = 0;
}

#if (project_name == "Project01")
if (!exists("default.values"))
  default.values = list(Age=70,Sex="M",Gender="M")
#if (project_name == "PING")
#  default.values = list(Age_At_IMGExam=70,Gender="M")


################################################################
# data directories
ddir    <- paste("/home/dataportal/www/data/", project_name, "/data_uncorrected", version, sep="")
udir    <- "/usr/share/nginx/html/applications/DataExploration/user_code"
vdir    <- "/usr/share/nginx/html/applications/DataExploration/user_data"
mainDir <- "/usr/share/nginx/html/applications/DataExploration/"
surfDir <- "/usr/share/nginx/html/applications/SurfaceViewer/"
##

# Test if the modification times of the input files are younger than the modification times of the 
# data file
if (!exists("datafile1"))
  datafile1 <- paste(ddir, paste(project_name, "_MRI_DTI_Complete_QCed.csv", sep=""), sep="/")
datafile2 <- paste(ddir, paste(project_name, "_Behavior.csv", sep=""), sep="/")
datafile3 <- paste(ddir, paste(project_name, "_SNP.csv", sep=""), sep="/")
datafile4 <- paste(vdir, paste("userdata_", project_name, "_", user_name, ".csv", sep=""), sep="/")
datafile5 <- paste(udir, paste("usercache_", project_name, "_", user_name, version, ".RData", sep=""), sep="/")
datafile5AsCsv <- paste(ddir, paste("usercache_", project_name, "_", user_name, version, ".csv", sep=""), sep="/")
if (file.exists(datafile1)) {
  df1time = file.info(datafile1)[,"mtime"]
}
if (file.exists(datafile2)) {
  df2time = file.info(datafile2)[,"mtime"]
}
if (file.exists(datafile3)) {
  df3time = file.info(datafile3)[,"mtime"]
}
if (file.exists(datafile4)) {
  df4time = file.info(datafile4)[,"mtime"]
}
reCreateCache <- 0
if (file.exists(datafile5)) {
  df5time = file.info(datafile5)[,"mtime"]
} else {
  reCreateCache <- 1
}
if ( reCreateCache == 0 && exists("df1time") && difftime(df1time, df5time) > 0 ) {
  reCreateCache <- 1
}
if ( reCreateCache == 0 && exists("df2time") && difftime(df2time, df5time) > 0 ) {
  reCreateCache <- 1
}
if ( reCreateCache == 0 && exists("df3time") && difftime(df3time, df5time) > 0 ) {
  reCreateCache <- 1
}
if ( reCreateCache == 0 && exists("df4time") && difftime(df4time, df5time) > 0 ) {
  reCreateCache <- 1
}

if (reCreateCache == 1) {
  tryCatch({
      if (file.exists(datafile1)) {
        dev.data <- read.csv(datafile1, header=T)
      }
      if (file.exists(datafile2)) {
        cog.data <- read.csv(datafile2, header=T)
      }
      if (file.exists(datafile3)) {
	snp.data <- read.csv(datafile3, na.strings="-9", header=T)
      }
      if (file.exists(datafile4)) {
	user.data <- read.csv(datafile4, header=T)
      }
  }) # tryCatch end  
  #
  # merge the four tables
  # (user data needs a SubjID column)
  if (exists("cog.data")) {
    byx = c("SubjID")
    # merge longitudinal data using subject-id and visit-id
    if ( !is.na(match("VisitID", names(dev.data))) & !is.na(match("VisitID", names(cog.data))) ) { 
       byx = c("SubjID","VisitID")
       cat("INFO: VisitID column found, assume we need to merge by SubjID and VisitID")
    } else {
       cat("INFO: No VisitID found")
    }
    # we should merge by removing duplicate columns (instead of creating .x,.y columns
    # this can be done like this:
    # data <- merge(dev.data, cog.data[,c("SubjID","VisitID",setdiff(colnames(cog.data),colnames(dev.data)))], by.x=c("SubjID","VisitID"), by.y=c("SubjID","VisitID"), all.x=T,all.y=T)
    data<-merge(dev.data, cog.data, by.x=byx, by.y=byx, all.x=F, all.y=T)
  } else {
    if (exists("dev.data")) {
      data<-dev.data  
    }
  }
  if (exists("snp.data")) {
    data<-merge(data, snp.data, by="SubjID", all.x=T, all.y=T)  
  }
  if (exists("user.data")) {
    byx = c("SubjID")
    # merge longitudinal data using subject-id and visit-id
    if ( !is.na(match("VisitID", names(data))) & !is.na(match("VisitID", names(user.data))) ) { 
       byx = c("SubjID","VisitID")
       cat("INFO: VisitID column found, assume we need to merge by SubjID and VisitID")
    } else {
       cat("INFO: No VisitID found")
    }
    data<-merge(data, user.data, by.x=byx, by.y=byx, all.x=T, all.y=T)
  }
  # cache the table for faster read - requires no merge operation
  try( save(data, file=datafile5), silent=TRUE );
  # create a down-loadable version
  try( write.csv(data, file=datafile5AsCsv, row.names=F), silent=TRUE );

  # lets save also some stat tables
  colsfile = paste(udir, paste("usercache_", project_name, "_", user_name, version, "_colnames.json", sep=""), sep="/")
  sink(colsfile, append=FALSE)
  cat(toJSON(colnames(data)))
  sink()
  colsfile = paste(udir, paste("usercache_", project_name, "_", user_name, version, "_summary.json", sep=""), sep="/")
  sink(colsfile, append=FALSE)
  cat(toJSON(summary(data)))
  sink()
  colsfile = paste(udir, paste("usercache_", project_name, "_", user_name, version, "_stats.json", sep=""), sep="/")
  if ( "Sex" %in% colnames(data) ) {
    gen  = "Sex"
  } else {
    if ( "Gender" %in% colnames(data) )
       gen = "Gender"
  }
  d0 = dim(data)[1]
  d1 = dim(data)[2]
  d2 = sum(is.na(as.vector(data)))
  # remove duplicates from longitudinal sessions
  uniqData <- subset(data, !duplicated(SubjID))
  d3 = sum(uniqData[[gen]]=="M",na.rm=TRUE)
  d4 = sum(uniqData[[gen]]=="F",na.rm=TRUE)
  d5 = ((length(unique(data$SubjID)) < length(data$SubjID))?'yes':'no');
  sink(colsfile, append=FALSE)
  cat(toJSON(c(d0,d1,d2,d3,d4,d5)))
  sink()
} else {
  if (file.exists(datafile5)) {
    load(datafile5);
  }
  if (!file.exists(datafile5AsCsv)) {
    try( write.csv(data, file=datafile5AsCsv, row.names=F), silent=TRUE );
  }
}

# in interactive mode we can load the provided data table
interactivemode = FALSE
if (!exists("data", mode="list")) {
  interactivemode = TRUE
  if (file.exists("usercache.RData")) {
    load("usercache.RData");
  }
}

dependent.measure=character()
covariates=character()
independent.variable=character()

rcode_include <- paste("curves/",user_name,"_",project_name,"_curves/rcode_include_",project_name,"_",user_name,"_",cookie,".txt",sep="")
if (file.exists(rcode_include)) {
  source(rcode_include)
} else { # read it from the current directory
  if (file.exists("rcode_include.txt")) {
     cat(sprintf("Warning: the standard include file could not be found. We will use a default include file now."))
     cat(sprintf(" Ignore this warning if you are running the model in interactive mode."))
     source("rcode_include.txt");
  }
}

# Construct formula & model from arguments

trim <- function (x) gsub("^\\s+|\\s+$", "", x)
mypaste <- function (x) { x = trim(x); x=x[x!=""]; paste(x,sep="",collapse=" + ") }

#covariates.full  = mypaste(c(covariates.ses,covariates.dev,covariates.gaf,covariates.usr))
#covariates.noses = mypaste(c(covariates.dev,covariates.gaf,covariates.usr))
#covariates.nodev = mypaste(c(covariates.ses,covariates.gaf,covariates.usr))
#covariates.nogaf = mypaste(c(covariates.ses,covariates.dev,covariates.usr))
#covariates.nousr = mypaste(c(covariates.ses,covariates.dev,covariates.gaf))

#if (covariates.full == "")
#  covariates.full = "1"

if (nchar(independent.variable)>3 & substring(independent.variable,1,2)=="s(" & 
    substring(independent.variable,nchar(independent.variable))==")")
  independent.variable.trimmed = substring(independent.variable,3,nchar(independent.variable)-1) else
  independent.variable.trimmed = independent.variable

# Remove independent and grouping variables from user- and system covariates
# Remove user covariates from system covariates
if (smoothing.interaction!="")
  varlist.exp = list(independent.variable.trimmed,smoothing.interaction) else
  varlist.exp = list(independent.variable.trimmed)
varlist.usr = setdiff(lapply(as.list(strsplit(covariates.usr, split="+", fixed=TRUE)[[1]]),trim),varlist.exp)
varlist.expusr = c(varlist.exp,varlist.usr)
varlist.ses = setdiff(lapply(as.list(strsplit(covariates.ses, split="+", fixed=TRUE)[[1]]),trim),varlist.expusr)
varlist.dev = setdiff(lapply(as.list(strsplit(covariates.dev, split="+", fixed=TRUE)[[1]]),trim),varlist.expusr)
varlist.gaf = setdiff(lapply(as.list(strsplit(covariates.gaf, split="+", fixed=TRUE)[[1]]),trim),varlist.expusr)
covariates.full = paste(c(varlist.ses,varlist.dev,varlist.gaf,varlist.usr),sep="",collapse=" + ")
covariates.noses = paste(c(varlist.dev,varlist.gaf,varlist.usr),sep="",collapse=" + ")
covariates.nodev = paste(c(varlist.ses,varlist.gaf,varlist.usr),sep="",collapse=" + ")
covariates.nogaf = paste(c(varlist.ses,varlist.dev,varlist.usr),sep="",collapse=" + ")
covariates.nousr = paste(c(varlist.ses,varlist.dev,varlist.gaf),sep="",collapse=" + ")

if (covariates.full == "")
  covariates.full = "1"

if (vertexwise == 1) {
  # redefine the dependent.measure for data filtering, we will use area for all of them
  # this reduces our data to all that have total area even for thickness and volume
  if (!is.null(data$MRI_cort_area.ctx.total)) {
    # check if the dependent variable we want to use is already in the list of covariates, don't add it
    # again because gam will remove it for allvars below
    if (sum(c(varlist.ses,varlist.dev,varlist.gaf,varlist.usr)=="MRI_cort_area.ctx.total")==0)
      dependent.measure = 'MRI_cort_area.ctx.total'
    } else if (!is.null(data$BrainMaskVolume)) {
      dependent.measure = 'BrainMaskVolume'
    } else if (!is.null(data$MRI_cort_area.Total)) {
      dependent.measure = 'MRI_cort_area.Total'
    } else if (!is.null(data$SubCort.Left.Hippocampus)) {
      dependent.measure = 'SubCort.Left.Hippocampus'
    } else if (!is.null(data$CortArea.Total)) {
      dependent.measure = 'CortArea.Total'
    }
}

# Remove rows missing any relevant variables
formula0 = formula(paste(dependent.measure," ~ ",covariates.full,sep=""))
model0.gam = gam(formula=formula0,data=data)
allvars = c(all.vars(model0.gam$formula),independent.variable.trimmed)
if (smoothing.interaction!="")
  allvars = c(allvars,smoothing.interaction)
colnums = which(is.element(names(data),allvars))
defvec = T
for (colnum in colnums) defvec = defvec & !is.na(data[,colnum])
data = data[defvec,]

# Keep only relevant subset of columns
required = c("Sex", "Gender", "StudyDate", "VisitID", "SubjID", "Age", "Site")
colnums = c(1:13,setdiff(which(is.element(names(data),c(dependent.measure,independent.variable.trimmed,smoothing.interaction,required,allvars))),c(1:13)))
data = data[,colnums]


if (!exists("params.k.main")) params.k.main = 4
if (!exists("params.k.inter")) params.k.inter = 3

# Define formulas (should handle linear and smooth effects -- make settable in interface, along with smoothing params)
if (independent.variable.trimmed == independent.variable) {
  main.effect.term = independent.variable.trimmed
} else {
  main.effect.term = paste("s(",independent.variable.trimmed,",bs=\"ts\",k=",params.k.main,")",sep="")
}
if (smoothing.interaction!="") {
  if (independent.variable.trimmed == independent.variable) {
    interaction.term = paste(independent.variable.trimmed,":",smoothing.interaction,sep="")
  } else {
    interaction.term = paste("s(",independent.variable.trimmed,",by=",smoothing.interaction,",bs=\"ts\",k=",params.k.inter,")",sep="") 
  }
  grouping.term   = paste(smoothing.interaction,sep="")
  formula.full    = formula(paste(dependent.measure," ~ ", mypaste(c(main.effect.term,grouping.term,interaction.term,covariates.full)),sep=""))  
  #formula.nomain = formula(paste(dependent.measure," ~ ",                      interaction.term," + ",covariates.full,sep=""))  
  formula.noint   = formula(paste(dependent.measure," ~ ",                      mypaste(c(main.effect.term,grouping.term,covariates.full)),sep=""))
  formula.nogroup = formula(paste(dependent.measure," ~ ",                      mypaste(c(main.effect.term,covariates.full)),sep=""))
  if (covariates.ses!="") formula.noses = formula(paste(dependent.measure," ~ ",mypaste(c(main.effect.term,interaction.term,covariates.noses)),sep=""))
  if (covariates.dev!="") formula.nodev = formula(paste(dependent.measure," ~ ",mypaste(c(main.effect.term,interaction.term,covariates.nodev)),sep=""))
  if (covariates.gaf!="") formula.nogaf = formula(paste(dependent.measure," ~ ",mypaste(c(main.effect.term,interaction.term,covariates.nogaf)),sep=""))
  if (covariates.usr!="") formula.nousr = formula(paste(dependent.measure," ~ ",mypaste(c(main.effect.term,interaction.term,covariates.nousr)),sep=""))
} else {
  formula.full = formula(paste(dependent.measure," ~ ",mypaste(c(main.effect.term,covariates.full)),sep=""))  
  if (covariates.ses!="") formula.noses = formula(paste(dependent.measure," ~ ",mypaste(c(main.effect.term,covariates.noses)),sep=""))
  if (covariates.dev!="") formula.nodev = formula(paste(dependent.measure," ~ ",mypaste(c(main.effect.term,covariates.nodev)),sep=""))
  if (covariates.gaf!="") formula.nogaf = formula(paste(dependent.measure," ~ ",mypaste(c(main.effect.term,covariates.nogaf)),sep=""))
  if (covariates.usr!="") formula.nousr = formula(paste(dependent.measure," ~ ",mypaste(c(main.effect.term,covariates.nousr)),sep=""))
}


# Fit models
model0 = gam(formula=formula0, data=data)
model.full = gam(formula=formula.full, data=data)
#model.nomain = gam(formula=formula.nomain, data=data) # This doesn't seem to work (wrong # of DOFs)
if (exists("formula.noint")) model.noint = gam(formula=formula.noint, data=data)
if (exists("formula.nogroup")) model.nogroup = gam(formula=formula.nogroup, data=data)
if (exists("formula.noses")) model.noses = gam(formula=formula.noses, data=data)
if (exists("formula.nodev")) model.nodev = gam(formula=formula.nodev, data=data)
if (exists("formula.nogaf")) model.nogaf = gam(formula=formula.nogaf, data=data)
if (exists("formula.nousr")) model.nousr = gam(formula=formula.nousr, data=data)

allvars = setdiff(all.vars(model0$formula),dependent.measure)
default.names = names(default.values)

#for (varname in allvars) {
#  if (is.element(varname,default.names)) {
#  	tmp = levels(data[[varname]])
#  	levels(data[[varname]]) = c(tmp[tmp==default.values[varname]],tmp[tmp!=default.values[varname]])
#  }
#}

# Compute predicted curves
independent.variable.seq = seq(min(data[[independent.variable.trimmed]],na.rm = TRUE),max(data[[independent.variable.trimmed]],na.rm = TRUE),length.out=100)
data.pred = cbind.data.frame(independent.variable.seq)
data.pred.names = c(independent.variable.trimmed)
if (smoothing.interaction!="") {
  group.levels = levels(data[[smoothing.interaction]])
  # remove empty elements
  group.levels = group.levels[group.levels != ""]
  if (is.null(group.levels)) {
    cat("## model summary\n")
    cat("Error: interaction term is not recognized as a categorical variable...\n")
  }
  data.pred = cbind.data.frame(data.pred,factor(NA,group.levels))
  data.pred.names = c(data.pred.names,smoothing.interaction)
} else {
  group.levels = c("dummy")
}
for (varname in allvars) {
  if (is.element(varname,data.pred.names))
    next

  if (is.element(varname,default.names))
    val = default.values[[varname]] 
  else {
      if (is.factor(data[[varname]]))
        val = levels(data[[varname]])[1] 
      else
        val = mean(data[[varname]],na.omit=TRUE)
  }
  data.pred = cbind.data.frame(data.pred,val)
  data.pred.names = c(data.pred.names,varname)
}
names(data.pred) = data.pred.names

data.curvefit = cbind.data.frame(independent.variable.seq)
data.curvefit.names = c(independent.variable.trimmed)
if (smoothing.interaction!="") {
  for (group in group.levels) {
    data.pred[[smoothing.interaction]] = factor(group,group.levels)
    y.pred = predict(model.full,newdata=data.pred)
    data.curvefit = cbind.data.frame(data.curvefit,y.pred)
    data.curvefit.names = c(data.curvefit.names,group)
  }
} else {
  y.pred = predict(model.full,newdata=data.pred)
  data.curvefit = cbind.data.frame(data.curvefit,y.pred)
  data.curvefit.names = c(data.curvefit.names,dependent.measure)
}
names(data.curvefit) = data.curvefit.names

# Construct version of data with standardized covars
covariates.full.list = all.vars(formula(paste(dependent.measure, " ~ ",covariates.full), sep=""));
covariates.full.list = covariates.full.list[covariates.full.list!=dependent.measure];
data.stdcovars = data
colnames.data = colnames(data)
for (varname in covariates.full.list) {
  if (is.element(varname,default.names)) {
    val = default.values[[varname]] 
  } else {
    if (is.factor(data[[varname]]))
      val = levels(data[[varname]])[1] 
    else
      val = mean(data[[varname]],na.omit=TRUE)
  }
  data.stdcovars[,varname==colnames.data] = val
}

# Compute residualized dependent.measure

y.obs = data[[dependent.measure]]
y.fit = predict(model.full,newdata=data)
y.stdcovars = predict(model.full,newdata=data.stdcovars)
y.res = y.obs - (y.fit-y.stdcovars)

if (0) {
  # Code to test residualization
  c(mean(y.obs[data$Sex=="M"]),mean(y.obs[data$Sex=="F"]),mean(y.fit[data$Sex=="M"]),mean(y.fit[data$Sex=="F"]),mean(y.res[data$Sex=="M"]),mean(y.res[data$Sex=="F"]))
  mean(data.curvefit[[dependent.measure]])
}

data.res = data
data.res[[dependent.measure]] = y.res
if (smoothing.interaction!="") {
  PlotGroup = data[[smoothing.interaction]]
  data.res = cbind.data.frame(data.res,PlotGroup)
}

mysummarize.model <- function (m0,row.name) {
  summ0=summary(m0); n=summ0$n; AICval=AIC(m0); BICval=BIC(m0); 
  k=(BICval-AICval)/(log(n)-2); data.frame(n=n,k=k,dev.expl=summ0$dev.expl,r.sq=summ0$r.sq,AIC=AICval,BIC=BICval,pChisq=NA,sig="",row.names=row.name) }

mysummarize.model.comparison <- function (m1,m0,row.name) {
  summ1=summary(m1); n=summ1$n; AICval=AIC(m1); BICval=BIC(m1); k=(BICval-AICval)/(log(n)-2); an=anova(m1,m0,test="Chisq");
  df=an$Df[2]
  if (df < 0.5)
     pval = "df<.5"
  else
     pval=tail(an$"Pr(>Chi)",1)
  s=""
  if (is.numeric(pval) && !is.na(pval)) {
    s="***"
    if (pval > 0.001)
      s=" **"
    if (pval > 0.01)
      s="  *"
    if (pval > 0.05)
      s="  ."
    if (pval > 0.1)
      s=" "
  }
  data.frame(n=n,k=k,dev.expl=summ1$dev.expl,r.sq=summ1$r.sq,AIC=AICval,BIC=BICval,pChisq=pval,sig=s,row.names=row.name) 
}

# Compile model comparison stats
pvaltable = mysummarize.model(model.full,"full")
if (exists("model.noint")) pvaltable = rbind(pvaltable,mysummarize.model.comparison(model.noint,model.full,"no interaction"))
if (exists("model.nogroup")) pvaltable = rbind(pvaltable,mysummarize.model.comparison(model.nogroup,model.full,"no grouping"))
if (exists("model.noses")) pvaltable = rbind(pvaltable,mysummarize.model.comparison(model.noses,model.full,"no SES"))
if (exists("model.nodev")) pvaltable = rbind(pvaltable,mysummarize.model.comparison(model.nodev,model.full,"no device"))
if (exists("model.nogaf")) pvaltable = rbind(pvaltable,mysummarize.model.comparison(model.nogaf,model.full,"no GAF"))
if (exists("model.nousr")) pvaltable = rbind(pvaltable,mysummarize.model.comparison(model.nousr,model.full,"no user"))

#tf <- terms(formula.full,"variables");
#l <- attr(tf, "term.labels");
#for (varname in l) {
#  rest <- paste(l[which(l!=varname)],sep="", collapse="+")
#  if (rest == "")
#     rest <- "1"
#  formulaWithout <- formula(paste(dependent.measure, " ~ ", rest, sep=""))
#  modelWithout.gam = gam(formulaWithout, data=data)
#  pvaltable = rbind(pvaltable,mysummarize.model.comparison(modelWithout.gam,model.full,varname))
#  #pval = tail(anova(modelWith.gam,modelWithout.gam,test="Chisq")$"Pr(>Chi)",1)
#  #cat(sprintf("p-val for main effect of %s = %g", varname, pval),"\n");
#}
 
##################################################
if (interactivemode == TRUE) {
  plot(x=data.res[[independent.variable.trimmed]], y=data.res[[dependent.measure]])
  lines(x=data.curvefit[[independent.variable.trimmed]], y=data.curvefit[,2])
  lines(x=data.curvefit[[independent.variable.trimmed]], y=data.curvefit[,3])
}
#################################################

# create vertex wise statistics
if (vertexwise == 1) {
  vertind = 325; # For debugging
  library(MASS)
  numModelSubjects = 0

  if (!interactivemode)
    ddir_vertex = paste(ddir,"VertexStatsInput", sep="/") else ddir_vertex = "";

  #  stats <- c("icoarea-lh", "icoarea-rh", "thick-lh", "thick-rh", "T1cont-projdist0.2-lh", "T1cont-projdist0.2-rh", "T1surf-projdist-0.2-lh", "T1surf-projdist-0.2-rh", "T1surf-projdist0.2-lh", "T1surf-projdist0.2-rh");
  hemispheres <- c("lh", "rh")
  # only compute what was requested
  stats_all <- c("icoarea", "thick", "volume", "geometry");
  if (request == -1) # no request, compute everything
    stats <- stats_all;
  stats <- c(stats_all[request+1], "geometry")   # values start with 1 in R, request counts from 0
  for (ss in stats) {
    # collect pvalues for both hemispheres and all three (or one) measures for FDR calculation
    listi = 3;
    if (smoothing.interaction == "")
      listi = 1;
    hemipvals  = vector("list", listi); # list of vectors
    log10pvals = vector("list", listi); # list of list of vectors
    for (h in hemispheres) {
      s <- paste(ss, '-', h, sep="");
      if (!interactivemode)
        fnamestem = paste(ddir_vertex,"/",project_name,"_concat_surfstats_sm2819-", s, sep="") else
        fnamestem = "PING_concat_surfstats_sm2819-icoarea-lh";
      datafile = paste(fnamestem,".csv",sep="")
      vertex.data = read.csv(datafile, na.strings="", header=T)
      fname = paste(fnamestem,".dat",sep="")
      fid = file(fname,"rb")
      dims = readBin(fid, integer(), n=3)
      nsubj = dims[1]
      nvert = dims[2]
      if (ss == "geometry")
	     nvert = nvert*3;
      vertvals = matrix(readBin(fid, numeric(), n=nsubj*nvert, size=4), nsubj, nvert)
      close(fid)

      # if we have a VisitID we need to match that one as well
      if ( !is.na(match("VisitID", names(vertex.data))) & !is.na(match("VisitID", names(data))) ) {
	# create new columns first which will be used during the merge
	data$SubjIDVisitID <- do.call(paste, c(data[c("SubjID", "VisitID")], sep = ""))
	vertex.data$SubjIDVisitID <- do.call(paste, c(vertex.data[c("SubjID", "VisitID")], sep = ""))
	# now select matching vertices
        subjidvec = intersect(data$SubjIDVisitID,vertex.data$SubjIDVisitID)
        ia = match(subjidvec,data$SubjIDVisitID)
        ib = match(subjidvec,vertex.data$SubjIDVisitID)
        data2 = data[ia,]; datamat = vertvals[ib,];
      } else { # merge by SubjID only
        subjidvec = intersect(data$SubjID,vertex.data$SubjID)
        ia = match(subjidvec,data$SubjID)
        ib = match(subjidvec,vertex.data$SubjID)
        data2 = data[ia,]; datamat = vertvals[ib,];
      }
      # cat(sprintf("size of data matrix is: %d %d\n", dim(data2)[1], dim(data2)[2]))
      numModelSubjects = length(subjidvec)
      if (exists("vertexShowSubjects") && vertexShowSubjects == 1) {
         vertexShowSubjects = sprintf("Subjects included in analysis:\n")
         subjectlist = paste(sprintf("%s,<br/>", data2$SubjID), collapse="")
	 vertexShowSubjects = paste(vertexShowSubjects, subjectlist)
      }

      formula = formula.full;
      model.gam = gam(formula,data=data2)
      if (exists("formula.nogroup"))
        model.r.gam=gam(formula.nogroup,data=data2)
      M = model.matrix(model.gam)
      Mi = vcov(model.gam,dispersion=1)%*%t(M)  #    Mi = ginv(M)
      C = Mi %*% t(Mi)
      H = Mi %*% M
      dof = sum(diag(H))

      if(exists("formula.nogroup")) {
        M.r = model.matrix(model.r.gam)
        Mi.r = vcov(model.r.gam,dispersion=1)%*%t(M.r)  #    Mi = ginv(M)
        C.r = Mi.r %*% t(Mi.r)
        H.r = Mi.r %*% M.r
        dof.r = sum(diag(H.r))
      }

      coeffmat = Mi %*% datamat
      datamat_hat = M %*% coeffmat
      errmat = (datamat_hat-datamat)^2
      dof.err = nrow(errmat)-dof
      sig2vec = colSums(errmat) / dof.err
      termlist = colnames(M)
      err_dev_full=sum(errmat)

      if (exists("formula.nogroup")) {
   	    err_dev_full=apply(errmat,2,sum)
        coeffmat.r = Mi.r %*% datamat
        datamat_hat.r = M.r %*% coeffmat.r
        errmat.r = (datamat_hat.r-datamat)^2
        dof.err.r = nrow(errmat.r)-dof.r
  	    err_dev_red=apply(errmat.r,2,sum)
        dev=err_dev_red-err_dev_full
	      sig0=err_dev_full/dof.err
      }

      #
      #  Compute and write vertexwise signed logpvec stats for main effects and interactions
      #
      if (smoothing.interaction!="") {
         indlists = list(grep(paste(glob2rx(paste(independent.variable,"*",sep="")),"[^:]*$",sep=""),termlist,value=FALSE),
                     grep(glob2rx(paste(smoothing.interaction,"*",sep="")),termlist,value=FALSE),
                     grep(glob2rx(paste(independent.variable,":",smoothing.interaction,"*",sep="")),termlist,value=FALSE))
      } else {
         indlists = list(grep(paste(glob2rx(paste(independent.variable,"*",sep="")),"[^:]*$",sep=""),termlist,value=FALSE))
      } 

      #    indlist = grep(paste(glob2rx(paste(independent.variable,"*",sep="")),"[^:]*$",sep=""), termlist, value=FALSE)    # Terms for main effect of independent variable
      #    indlist = grep(glob2rx(paste(smoothing.interaction,"*",sep="")), termlist, value=FALSE) # Main effect of grouping variable
      #    indlist = grep(glob2rx(paste(independent.variable,":",smoothing.interaction,"*",sep="")), termlist, value=FALSE) # Interaction terms

      #indlists = list(grep(paste(glob2rx(paste(independent.variable,"*",sep="")),"[^:]*$",sep=""),termlist,value=FALSE),
      #             grep(glob2rx(paste(smoothing.interaction,"*",sep="")),termlist,value=FALSE),
      #             grep(glob2rx(paste(independent.variable,":",smoothing.interaction,"*",sep="")),termlist,value=FALSE))
      logpmat = matrix(NA,length(indlists),nvert)
      for (listi in 1:length(indlists)) {
        indlist = indlists[[listi]]

        # Compute t- and p-values for each
        if (length(indlist)==1) { # single-coefficient case (t-test)
          sevec = sqrt(C[indlist,indlist]*sig2vec)
          tvec = coeffmat[indlist,]/sevec
          pvec = 2*pt(-abs(tvec),df=dof.err)

 	        hemipvals[[listi]] = append(hemipvals[[listi]], pvec);
          logpvec = -log10(pvec)*sign(tvec)
 	        log10pvals[[listi]][[h]] = logpvec;
        } else {                  # multiple coefficient case 
          I = diag(ncol(M))
          R = I[indlist,]
          Rh = R %*% coeffmat
          # formula in eq. 11 from http://spin.ecn.purdue.edu/fmri/PDFLibrary/BurockM_HBM_2000_11_249_260.pdf
          Fvec = (colSums(Rh * (ginv(R %*% ginv(t(M) %*% M) %*% t(R)) %*% Rh))/nrow(R))/sig2vec 
          # cat(sprintf("DIMENSIONS: %d \n", length(Fvec)))
          if (exists("formula.nogroup")) {
             Fvec=dev/sig0
             dof.eff=dof.err.r-dof.err
          } else {
            dof.eff = sum(diag(H[indlist,indlist])) # Effective number of degrees of freedom for smooth terms
          }
          # pvec = pf(Fvec,df1=nrow(R),df2=dof.err,lower.tail=FALSE) # Use nominal degrees of freedom
          pvec = pf(Fvec,df1=dof.eff,df2=dof.err,lower.tail=FALSE) # Use effective degrees of freedom

	        hemipvals[[listi]] = append(hemipvals[[listi]], pvec);

          logpvec = -log10(pvec)
 	        log10pvals[[listi]][[h]] = logpvec;
        }
        logpmat[listi,] = logpvec;

        # if we don't signal that we are done successfully we are handeled by executeR like an error
        cat("## model summary\n")

        if (FALSE) { # debugging code
          formula = update.formula(formula.full, paste("datamat[,",vertind,"]"," ~ .", sep=""))
          model.gam = gam(formula,data=data2)
          cat("## model summary\n")
          summary(model.gam)
          cat("## vertexwise parameter estimates\n")
          cat("##   p = ",pvec[vertind])
          if (length(indlist)==1) {
            cat("##   t = ",tvec[vertind])
          } else {
            cat("##   F = ",Fvec[vertind])
          }
        }
      } # listi loop end

      #
      # Write predicted values of dependent measure as function of independent variable and group level
      # (need to write out more than one file, one for each group)
      if (exists("group.levels")) {
        # write the matrix to json-file

#        fname = "/tmp/blub.json"
#        fcontent = list( entries = list() );

        fname = paste(vdir, "/", "vertexdata_", project_name, "_", user_name, "_", cookie, "_", s, ".json", sep="")
        sink(fname, append=FALSE)
        cat(sprintf("{ \"transformations\": [ { \"x\": \"1\", \"y\": \"0\", \"z\": \"0\", \"rad\": \"-0.3\" }, { \"x\": \"0\", \"y\": \"0\", \"z\": \"1\", \"rad\": \"0.05\" } ], \"entries\": [ "));
        group_number = 1
        for (group in group.levels) {
          if (group_number > 1) # export only a single group to make page react faster
             break
          
          if (group_number > 1)
            cat(sprintf(", "));

          if (smoothing.interaction != "")
            data.pred[[smoothing.interaction]] = factor(group,group.levels)
          Mp = predict(model.gam,newdata=data.pred,type="lpmatrix")
          ymat.pred = Mp %*% (Mi %*% vertvals[ib,])
          # add to this matrix the slope for each column (rate of change over age)

          tmp = data[[independent.variable.trimmed]]
          predictorRange = range(tmp)
          tmp = data.pred[[independent.variable.trimmed]]
          dx = tmp[2]-tmp[1]
          tmp = diff(ymat.pred)
          dymat.pred = (rbind(tmp[1,],tmp)+rbind(tmp,tmp[nrow(tmp),]))/2/dx
          # calculate the rate of change in percentages of the predictor range normalized change
          # (every vertex has a mean, rate of change is percentages difference from that mean)
          dymat.pred = 100*dymat.pred / matrix(colMeans(ymat.pred),dim(ymat.pred)[1],dim(ymat.pred)[2],byrow=T)
          # dymat.pred = (rbind(0,tmp)+rbind(tmp,0))/2/dx

	        if (!exists("predictedRange")) {
            valvec = c(ymat.pred);
            rangevals = quantile(valvec,c(0.01,.99),na.rm=TRUE);
            tmp = max(abs(rangevals)); rangevals = c(-tmp,tmp); # Force to be symmetric
          } else {
            rangevals = predictedRange;
          }	
  
          if (!exists("rateOfChangeRange")) { 
            valvec = c(dymat.pred);
            drangevals = quantile(valvec,c(0.01,.99),na.rm=TRUE);
            tmp = max(abs(drangevals)); drangevals = c(-tmp,tmp); # Force to be symmetric
          } else {
            drangevals = rateOfChangeRange;
          }

#          valvec = c(ymat.pred);
#          rangevals = quantile(valvec,c(0.01,.999));
#          tmp = max(abs(rangevals)); rangevals = c(-tmp,tmp);
#          tmp = data[[independent.variable.trimmed]]
#          dx = tmp[2]-tmp[1]
#          predictorRange = range(tmp)
	   
#          tmp = diff(ymat.pred)
#          dymat.pred = (rbind(0,tmp)+rbind(tmp,0))/2/dx
#          valvec = c(dymat.pred);
#          drangevals = quantile(valvec,c(0.01,.99));

          ntime = dim(ymat.pred)[1]
          nvert = dim(ymat.pred)[2]
          type = "vertex measure"
          if (ss == "geometry")
	     type = "geometry"

          # store values and values2 without names
	  #valuesMatrix = t(ymat.pred)
          #valuesMatrix[valuesMatrix == Inf]  = max(is.finite(valuesMatrix))
          #valuesMatrix[valuesMatrix == -Inf] = min(is.finite(valuesMatrix))
          #valuesMatrix[!is.finite(valuesMatrix)] = NULL
	  #valuesMatrix = round(valuesMatrix,digits=3)
	  #fcontent$entries[[group_number]] = list( ntime = ntime, nvert = nvert, group = group, type = type, range = predictorRange, windowLevel = rangevals, windowLevel2 = drangevals, values = lapply(apply(valuesMatrix,2,list), simplify2array), values2 = lapply(apply(t(format(dymat.pred,digits=3)),2,list),simplify2array) )

          cat(sprintf("{ \"ntime\": %d, \"nvert\": %d, \"group\": \"%s\", \"type\": \"%s\", \"range\": [ %f, %f ], \"windowLevel\": [ %f, %f ], \"windowLevel2\": [ %f, %f ],\n",
	              ntime, nvert, group, type, predictorRange[1], predictorRange[2], rangevals[1], rangevals[2], drangevals[1], drangevals[2] ) )
          cat(sprintf("  \"values\": ["))
          for (i in 1:ntime) {
            # save some space by using 3 digits only
            # we need to use a vertex buffer object here!!
            cat("[ ")
            valvec = ymat.pred[i,]
            valvec[valvec==Inf]  = max(valvec[is.finite(valvec)])
            valvec[valvec==-Inf] = min(valvec[is.finite(valvec)])
	          tmp = format(valvec,digits=3)
            tmp[!is.finite(valvec)] = "null"
            cat(paste(tmp,sep="",collapse=","),"\n")
            if (i==ntime)
              cat("]\n")
            else
              cat(" ],\n")
          }
          cat(sprintf("  ], "))

          cat(sprintf("  \"values2\": ["))
          for (i in 1:ntime) {
            # save some space by using 3 digits only
            # we need to use a vertex buffer object here!!
            cat("[ ")
            valvec = dymat.pred[i,]
            valvec[valvec==Inf] = max(valvec[is.finite(valvec)]) 
            valvec[valvec==-Inf] = min(valvec[is.finite(valvec)]) 
	          tmp = format(valvec,digits=3)
            tmp[!is.finite(valvec)] = "null"
            cat(paste(tmp,sep="",collapse=","),"\n")
            # cat(paste(format(dymat.pred[i,],digits=3),sep="",collapse=","),"\n")
            if (i==ntime)
              cat("]\n")
            else
              cat(" ],\n")
          }
          cat(sprintf("  ]\n}"))

          group_number = group_number + 1
        }
        cat(sprintf("] }\n"));
        sink()
        # write result
        #sink(fname, append=FALSE)
        #cat(toJSON(fcontent))
        #sink()
      }
    } # end both hemispheres

    # calculate FDR values on hemipvals (total brain for each term)

    # load the mask json files for left and right hemisphere
    mask_lh <- sprintf('%s/data/ico5_lh_surfmask.json', surfDir)
    mask_rh <- sprintf('%s/data/ico5_rh_surfmask.json', surfDir)  
    mask_data_lh <- fromJSON(paste(readLines(mask_lh), collapse=""))
    mask_data_rh <- fromJSON(paste(readLines(mask_rh), collapse=""))
    # the order is imporant here, we use lh,rh because of 'hemispheres'
    mask_data = mask_data_lh$values
    mask_data = append(mask_data, mask_data_rh$values)
    if (ss == "geometry") { # add mask for y and z component as well
       mask_data = mask_data_lh$values
       mask_data = append(mask_data, mask_data)
       mask_data = append(mask_data, mask_data)
       mask_data = append(mask_data, mask_data_rh$values)
       mask_data = append(mask_data, mask_data_rh$values)
       mask_data = append(mask_data, mask_data_rh$values)
    }

    fdrs = array(NA,length(hemipvals))
    for (i in 1:length(hemipvals)) {
          # calculate the FDR threshold from hemipvals (left followed by right)
          pvec = hemipvals[[i]]
          pvec = pvec[mask_data==1]    # filter by vertex mask

          q = .05
          p = sort(pvec)
          V = length(pvec)
          I = cbind(1:V)
          cVID = 1;
          cVN = sum(1/(1:V));
          # pID = pvec[max((1:V)[pvec<=I/V*q/cVID])]
          # pN  = pvec[max((1:V)[pvec<=I/V*q/cVN])]

# Plot what is going on
#   hist(pvec, breaks=30)
#   hist(log10pvals[[1]][[1]],breaks=30)  # lh
#   hist(log10pvals[[1]][[2]],breaks=30)  # rh
#
#   plot(1:length(p)/length(p),p)
#   lines(x=c(0,1),y=c(0,q/cVN))

	  # find out if p[] is empty
          belowThreshold = p[p < seq(0,1,length.out=length(p))*q/cVN]
	  if (length(belowThreshold) == 0) {
	    pID = NA;
	  } else {
            pID = max(belowThreshold)
          }

          if ( is.na(-log10(pID)) ) {
             fdrs[i] = -1; # indicate that nothing survives the threshold
          } else {
             fdrs[i] = -log10(pID);
          }
    }

    # save out results in log10pvals (for each hemisphere for the current measure)
    for (h in names(log10pvals[[1]])) {
      # write out the matrix as a json file
      s <- paste(ss, '-', h, sep="");
      fname = paste(vdir, "/", "vertexdata_main_effect_and_interaction_", project_name, "_", user_name, "_", cookie, "_", s, ".json", sep="")
      logpmat = matrix(NA,length(log10pvals),length(log10pvals[[1]][[h]]))
      for (i in 1:length(log10pvals)) {
        logpmat[i,] = log10pvals[[i]][[h]];
      }
      ntime = dim(logpmat)[1]
      nvert = dim(logpmat)[2]
      sink(fname, append=FALSE)
      cat(sprintf("{ \"ntime\": %d, \"nvert\": %d, \"windowLevel\": [-5, 5],\n", ntime, nvert))
      cat(sprintf("  \"terms\": [ \n") )
      if (smoothing.interaction!="") {
         cat(sprintf("  \"independent.variable\", \"interaction.variable\", \"independent.variable by interaction.variable\"\n"))
      } else {
         cat(sprintf("  \"independent.variable\"\n"))
      }
      cat(sprintf("  ],\n"))
      cat(sprintf(" \"FDR_Threshold\": [ \n") );
      for (t in 1:length(fdrs)) {
          val = fdrs[[t]]
          if (val == Inf)
              val = "-1"
          if (is.character(val)) cat(sprintf(" %s", val)) else cat(sprintf(" %f", val))
          if (t < length(fdrs)) 
  	     cat(sprintf(","));
      }
      cat(sprintf(" ],\n"));
      cat(sprintf("  \"values\": ["))
      for (i in 1:ntime) {
        # save some space by using 3 digits only
        # we need to use a vertex buffer object here!!
        cat("[ ")
        valvec = logpmat[i,]
        valvec[valvec==Inf]  = max(valvec[is.finite(valvec)])
        valvec[valvec==-Inf] = min(valvec[is.finite(valvec)])
	      tmp = format(valvec,digits=3)
        tmp[!is.finite(valvec)] = "null"
        cat(paste(tmp,sep="",collapse=","),"\n")
        # cat(paste(format(logpmat[i,],digits=3),sep="",collapse=","),"\n")
        if (i==ntime)
          cat("]\n")
        else
          cat(" ],\n")
      }
      cat(sprintf("  ]\n}"))
      sink()
    } # end for both hemispheres
  }

  # add some statistics for vertex measures as well
  options("width"=160)
  cat("## model summary\n")
  if (version == "")
   cat("## data version: latest", "\n") else
   cat("## data version: ", version, "\n")

  # number of vertices is from geometry (above), three times to high, both hemispheres -> /3*2
  fname = paste(vdir, "/", "vertexdata_stats_", project_name, "_", user_name, "_", cookie, ".txt", sep="")
  sink(fname, append=FALSE)
  #cat(sprintf("Number of vertices (both hemispheres): %d<br/>", nvert/3*2))
  #cat(sprintf("Number of subjects included: %d (out of %d)<br/>", numModelSubjects, nsubj))
  if ( exists("vertexShowSubjects") )
    cat( vertexShowSubjects )
  sink()

  cat(sprintf("Number of vertices (both hemispheres): %d\n", nvert/3*2))
  cat(sprintf("Number of subjects included: %d (out of %d)\n", numModelSubjects, nsubj))
  q()
}

# Write out .csv files

dir.create(file.path(mainDir, "curves"))
curvedirname <- paste("curves/", user_name, "_", project_name, "_curves", sep="")
dir.create(file.path(mainDir, curvedirname))
write.csv(data.curvefit,paste(mainDir,curvedirname,'/',dependent.measure,cookie,".tsv",sep=""),row.names=F)

# we have to use tsv here because the browser otherwise reads it wrong
write.csv(data.res, paste(mainDir,curvedirname,"/",user_name, "_", project_name, "_Corrected",cookie,".tsv",sep=''), row.names=F)

options("width"=160)
cat("## model summary\n")
if (version == "")
 cat("## data version: latest", "\n") else
 cat("## data version: ", version, "\n")

cat("## Coefficient estimates and p-values for linear terms, and approximate statistics for smooth terms\n")
# remove the 'NA' value because it is not sensical (comparison of 'full' with 'full')
pvaltable[1,7] = '-'
summary(model.full)

cat("\n## p-values for factors\n")
# summary(model.full)$pTerms.table
pv <- summary(model.full)$pTerms.table
if (!is.null(pv) && dim(subset(pv, pv[,1]>1))[1] > 0)
  subset(pv, pv[,1]>1)

cat("\n## Model comparison statistics against the full model\n")
cat("## (different sets of variables are excluded as a group)\n")
pvaltable

q();

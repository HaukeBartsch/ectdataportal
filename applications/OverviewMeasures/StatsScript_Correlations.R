################################################################
#
## GAM's
#

library(mgcv)

# read json files with rjson, install with: R CMD INSTALL rjson_0.2.11.tar
library("rjson")

#
# In order to run this model use the following command line for R:
#   /usr/bin/R --no-restore --no-save -q -f StatsScript_Scatter.R --args project_name="Project01" user_name="ME" a="CortArea.Total" b="Age"
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
  version      = "";
  a            = "SubCort.Left.Hippocampus"
  b            = "Age"
}

################################################################
# data directories
ddir    <- paste("/home/dataportal/www/data/", project_name, "/data_uncorrected", version, sep="")
udir    <- "/home/dataportal/www/applications/DataExploration/user_code"
mainDir <- "/home/dataportal/www/applications/OverviewMeasures/"

datafile5 <- paste(udir, paste("usercache_", project_name, "_", user_name, version, ".RData", sep=""), sep="/")

if (file.exists(datafile5)) {
    load(datafile5);
} else {
    cat("## model summary\n")
    cat("Error: could not read the cached data file, try to run DataExploration for this user first...\n")
    return
}

dir.create(file.path(mainDir, "curves"))
curvedirname <- paste("curves/", user_name, "_", project_name, "_curves", sep="")
dir.create(file.path(mainDir, curvedirname))

#required = c("Sex", "Gender", "StudyDate", "VisitID", "SubjID", "Age", "Site")
#colnums = c(1:2,setdiff(which(is.element(names(data),c(a,b,required))),c(1:2)))
#data = data[,colnums]

a <- sapply(data,is.numeric)
nams = names(data)[a]
# this will take a long time
cor.mat = cor(data[a],use="pairwise.complete.obs")

# this does not work
p <- 30 # how many top items
n <- ncol(cor.mat)
cmat <- col(cor.mat)
ind <- order(-cmat, cor.mat, decreasing = TRUE) - (n * cmat - n)
dim(ind) <- dim(cor.mat)
ind <- ind[seq(2, p + 1), ]
#out <- cbind(ID = c(col(ind)), ID2 = c(ind)) 
#bla <- as.data.frame(cbind(out,cor = cor.mat[out]))

#out2 <- cbind(ID = nams[col(ind)], ID2 = nams[ind]) 
out2 <- cbind(ID = c(col(ind)), ID2 = c(ind)) 
bla2 <- as.data.frame(cbind(out2,cor = cor.mat[out2]))
bla3 <- bla2[ order(bla2[,3], decreasing=T), ]

#
# bla3 now contains three columns |name measure 1|name measure 2|correlation|
#
corfile = paste(mainDir,curvedirname,"/",user_name, "_", project_name, "_Correlations.json", sep="")
sink(corfile, append=FALSE)
cat(toJSON(bla3))
sink()

corfile = paste(mainDir,curvedirname,"/",user_name, "_", project_name, "_CorrelationsNames.json", sep="")
sink(corfile, append=FALSE)
cat(toJSON(nams))
sink()

options("width"=160)
cat("## model summary\n")
if (version == "")
 cat("## no information", "\n") else

q();

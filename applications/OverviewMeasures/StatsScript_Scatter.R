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

required = c("Sex", "Gender", "StudyDate", "VisitID", "SubjID", "Age", "Site")
colnums = c(1:2,setdiff(which(is.element(names(data),c(a,b,required))),c(1:2)))
data = data[,colnums]

write.csv(data, paste(mainDir,curvedirname,"/",user_name, "_", project_name, "_Scatter.tsv",sep=''), row.names=F)

options("width"=160)
cat("## model summary\n")
if (version == "")
 cat("## some information", "\n") else

q();

################################################################
#
## R script that prepares the data for GWAS
#  requires: existing user cache
#

library("rjson")

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
version=""

if (!exists("project_name")) {
   project_name = "PING";
   user_name = "admin";
   cookie = "42";
   id="42";
}

# load the model
ddir    <- paste("/home/dataportal/www/data/", project_name, "/data_uncorrected", version, sep="")
udir    <- "/home/dataportal/www/applications/DataExploration/user_code"
datafile5 <- paste(udir, paste("usercache_", project_name, "_", user_name, version, ".RData", sep=""), sep="/")

# load the project raw data
if (file.exists(datafile5)) {
  load(datafile5);
} else {
  # we cannot do anything without data
  cat(sprintf('Error: no data found'));
  return;
}

rcode_include <- paste("data/",id,".model",sep="")
if (file.exists(rcode_include)) {
  source(rcode_include)
}

# load in the correct order for the subjects
orderfile <- paste(ddir, "SNPs/runs/pheno_EMMEX.csv", sep="/")
if ( file.exists(orderfile) ) {
  of <- read.delim(orderfile, header = FALSE, sep=" ")
  # column names are now V1, V2, V3
} else {
  cat(sprintf('Error: could not load the default order file pheno_EMMEX.csv'));
  return;
}

# all missing data should be removed
# find out subset of subjects with all information
a1 <- data[,c("SubjID",yvalue)]
a2 <- data[,c("SubjID",covariates)]
erg <- merge(a1, a2, by.x=c("SubjID"), by.y=c("SubjID"))
erg <- merge(erg, of, by.x=c("SubjID"), by.y=c("V2"),all.x = TRUE)
erg <- erg[complete.cases(erg), ]
cat(sprintf('We have %s complete cases...', nrow(erg)))
# order them just like in the ped/fam files
o <- order(erg$V1)
erg <- erg[o,]

# add the family id
plinkf <- erg[,c("V1", "SubjID")]
# write out for plink
fn <- paste("data/",id,"_plink_Complete_Subjects.txt",sep="")
write.table(plinkf, file=fn,quote=FALSE,sep=" ",col.names=FALSE,row.names=FALSE)

# for plink we need a file with the family, subject ids
# /usr/local/bin/plink --noweb --bfile PING_660_final --recode12 --output-missing-genotype 0 --transpose --out PING_660_final_tped
# /usr/local/bin/plink --noweb --bfile PING_660_final_tped --output-missing-genotype 0 --make-bed --keep 42_plink_Complete_Subjects.txt --out 42_PING_660_final_tped
#  ~/www/data/PING/data_uncorrected/SNPs$ /usr/local/bin/plink --noweb --bfile PING_660_final_tped --output-missing-genotype 0 --make-bed --keep ~/www/applications/GWAS/data/42_plink_Complete_Subjects.txt --out ~/www/applications/GWAS/data/42_PING_660_final_tped_keep

# for emmax-kin we need:
# /usr/pubsw/packages/emmax/emmax-kin -v -h -s -d 10 42_PING_660_final_tped
# /usr/pubsw/packages/emmax/emmax-kin -v -h -d 10 42_PING_660_final_tped
# next create pheno files

# grab the yvalue column from data and merge with orderfile - and keep the order!
pheno <- erg[,c("V1","SubjID",yvalue)]

# write out for emmax
fn <- paste("data/",id,"_pheno_EMMAX.csv",sep="")
write.table(pheno, file=fn,quote=FALSE,sep=" ",col.names=FALSE,row.names=FALSE)

# next spreadsheet is for covariates
# we need to recode each categorical variable
erg$ones = 1
levelList <- list();
if (!is.factor(erg[[covariates]])) {
  covar <- erg[,c("V1","SubjID","ones",covariates)]
} else {
  # variable is a factor
  covar <- erg[,c("V1","SubjID","ones")]
  levs <- levels(erg[[covariates]])
  levs <- levs[-length(levs)]
  # for each level remaining level
  for (level in levs) {
    vn <- paste(covariates,level,sep="")
    levelList <- list(c(unlist(levelList),vn))
    covar[[vn]] = as.numeric(erg[[covariates]] == level)
  }
}

# write out for emmax
fn <- paste("data/",id,"_pheno_EMMAX_covariates.csv",sep="")
write.table(covar, file=fn,quote=FALSE,sep=" ",col.names=FALSE,row.names=FALSE)

# write out stat information (how many variables, what level)
fn <- paste("data/",id,"_stat.json",sep="")
df <- data.frame(numSubjects=dim(pheno)[1])
if (length(unlist(levelList)) > 0) {
  df$covars = paste(unlist(levelList),collapse=", ",sep="")
} else {
  df$covars = covariates
}
sink(fn, append=FALSE)
cat(toJSON(df))
sink()

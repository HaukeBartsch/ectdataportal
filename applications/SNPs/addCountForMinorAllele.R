
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

# assume that we have a variable fntable
data <- read.csv(fntable)
numColumns <- dim(data)[2]-1

for (i in 1:numColumns) {
  t <- sort(table(data[,i+1]))
  alleles <- unique(strsplit(paste(names(t), collapse=''),'')[[1]])
  # allele frequency
  af <- alleles
  names(af) <- alleles
  af[1:2] <- 0
  # what is the minor allele (count and sort)?
  af[strsplit(names(t)[1], '')[[1]][[1]]] = as.numeric(af[strsplit(names(t)[1], '')[[1]][[1]]]) + t[[1]]  
  af[strsplit(names(t)[1], '')[[1]][[2]]] = as.numeric(af[strsplit(names(t)[1], '')[[1]][[2]]]) + t[[1]]  
  af[strsplit(names(t)[2], '')[[1]][[1]]] = as.numeric(af[strsplit(names(t)[2], '')[[1]][[1]]]) + t[[2]]  
  af[strsplit(names(t)[2], '')[[1]][[2]]] = as.numeric(af[strsplit(names(t)[2], '')[[1]][[2]]]) + t[[2]]  
  af[strsplit(names(t)[3], '')[[1]][[1]]] = as.numeric(af[strsplit(names(t)[3], '')[[1]][[1]]]) + t[[3]]  
  af[strsplit(names(t)[3], '')[[1]][[2]]] = as.numeric(af[strsplit(names(t)[3], '')[[1]][[2]]]) + t[[3]]  
  af <- sort(af)
  ma <- names(af)[[1]]
  
  nn <- sprintf("%s_count%s", names(data)[i+1], ma)
  data[[nn]] <- 0
  data[[nn]][data[,i+1] == names(t)[1]] <- sum(strsplit(names(t)[[1]],'')[[1]] == ma, na.rm=TRUE) - 1
  data[[nn]][data[,i+1] == names(t)[2]] <- sum(strsplit(names(t)[[2]],'')[[1]] == ma, na.rm=TRUE) - 1
  data[[nn]][data[,i+1] == names(t)[3]] <- sum(strsplit(names(t)[[3]],'')[[1]] == ma, na.rm=TRUE) - 1
}
# overwrite the old table
write.csv(data,file=fntable,row.names=FALSE)

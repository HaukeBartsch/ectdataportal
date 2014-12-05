# Post-processing of incoming spreadsheets
#   Load a table and a dictionary and process both.
#   The dictionary can contain a column "Hide" that indicates
#   with an integer if a column in the table should be hidden (removed)
#   a second column 'Code' specifies how to anonymize a column in
#   the spreadsheet by encoding key-value pairs separated by ','.
# Usage:
#   R -f recode.R
#

project='Project01'
fndict  = paste('/home/dataportal/www/data/',project,'/data_uncorrected/',project,'_datadictionary01.csv', sep="")
fntab   = paste('/home/dataportal/www/data/',project,'/data_uncorrected/',project,'_MRI_DTI_Complete.csv',sep="")
convout = paste('/home/dataportal/www/data/',project,'/data_uncorrected/',project,'_MRI_DTI_Complete_Filtered.csv',sep="")

#fndict  = paste('/home/dataportal/www/data/',project,'/data_uncorrected/',project,'_datadictionary02.csv', sep="")
#fntab   = paste('/home/dataportal/www/data/',project,'/data_uncorrected/',project,'_Behavior.csv',sep="")
#convout = paste('/home/dataportal/www/data/',project,'/data_uncorrected/',project,'_Behavior_Filtered.csv',sep="")

dict = read.csv(fndict, header=TRUE)
tab = read.csv(fntab, header=TRUE)

# if we have a 'Hide' columns read as 0/1 and remove columns if set to 1
if  ( 'Hide' %in% colnames(dict) ) {
   cat(paste("filter columns\n"))
   columnstoremove = dict[(dict[['Hide']]==1),1]
   # remove any leading and trailing spaces
   columnstoremove = gsub("(^ +)|( +$)", "", columnstoremove)
   # we need to keep some columns, those should never be removed
   keep = c('SubjID', 'VisitID')
   rem = setdiff(columnstoremove, keep)
   # now remove those columns   
   tab <- tab[, !(names(tab) %in% rem)]
   cat(paste("the following list of columns have been removed: \n  "))
   cat(paste(rem, sep=","))   
} else cat(paste("error: no column named 'Hide' found, no column will be hidden"))

# next re-code columns
if ( 'Code' %in% colnames(dict) ) {
   columnstorecode = dict[(dict[['Code']]!=""),1]
   code = dict[(dict[['Code']]!=""),'Code']
   for ( i in 1:length(columnstorecode) ) {
   	 key = gsub("(^ +)|( +$)", "", columnstorecode[i])
   	 val = code[i]
   	 cat(paste('recode entry', key, 'with values', val, "\n"));
   	 l = strsplit(as.character(gsub("\"", "", val)),"[,]")
   	 for (j in seq(1,length(l[[1]]), by=2)) {
   	    k = l[[1]][j]
   	    v = l[[1]][j+1]
   	    if (is.factor(tab[[key]])) {
     	        ll = as.character(tab[[key]])
                ll[ll==k]=v
                newentry = as.factor(ll)
     	    } else {
     	        ll = tab[[key]]
                ll[ll==k]=v
                newentry = ll
            }
            tab[[key]] = newentry
         }
   }
} else cat(paste("error: no column named 'Code' found, no columns will be recoded"))

write.csv(tab, file=convout, row.names=FALSE)
warnings()

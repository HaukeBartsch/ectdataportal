Files in this directory are related to the installation of the portal on a novel machine. We need for example R (2.15) and the rjson package for the DataExploration application.

A second requirement on the machine is git as a backend for the DataAdmin package. This package also requires to run:
   > sudo yum install php-posix
If not the error message will be: Call to undefined function posix_getpwuid()

Plink is used to extract SNP data from binary (plink) files. The zip file contains a 64bit executable that can be copied into some path on the local machine like /usr/local/bin/.

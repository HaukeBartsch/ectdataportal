INSTALLATION

On the deployment machine make sure that php and apache are installed and running. Create a user that will host the web-site called dataportal. The site is deployed using git. The required git repositories are created by the install.sh script.

> cd
> mkdir -p src/dataportal/
> cd src/dataportal
> cp <from the source>/install.sh .
> ./install.sh
> sudo cp dataportal.conf /etc/apache2/site-enabled/

Clone the repository from a local machine:
  git clone <user>@<servername>:/home/ping/src/dataportal/dataportal_hub.git dataportal
Push a change to the repository and the files are created in ~/www. Connect to the page
using http://localhost:3000. The page is using basic authentication with user name
"dataportal" and password "dataportal". You can change these settings by changing ~/.passwords
using /usr/bin/htpasswd and /etc/apache2/site-enabled/dataportal.conf.

Make sure permissions are set correctly on the machine. This includes:
 - make data/Project01/ read/writable by the web-server
 - make data/.git read/writable by the web-server


(Hauke Bartsch, hbartsch@ucsd.edu)

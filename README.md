# ECT multi-center study

To create a working version of the portal do the following:

- go somewhere safe
- check out the website:
```
    git clone github.com/HaukeBartsch/ectdataportal
```
- start the mb docker container using:
```
    docker run -i -t -p 8011:80 -p 2812:2812 -v `pwd`:/var/www/nginx/html 137.110.172.79:5000/mb /bin/bash -l
```
- connect to the user interface on
```
    boot2docker ip;
    http://192.168.59.103:8011/index.html
```

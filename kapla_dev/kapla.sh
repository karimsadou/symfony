#!/bin/bash

mkdir src
cd src
mkdir kapla
cd kapla
git clone https://bitbucket.acensi.fr/scm/in/richtext.git
git clone https://bitbucket.acensi.fr/scm/in/video.git
git clone https://bitbucket.acensi.fr/scm/in/gallery.git
git clone https://bitbucket.acensi.fr/scm/in/upload.git
git clone https://bitbucket.acensi.fr/scm/in/user.git
git clone https://bitbucket.acensi.fr/scm/in/admin.git
git clone https://bitbucket.acensi.fr/scm/in/pagelist.git
git clone https://bitbucket.acensi.fr/scm/in/page.git
git clone https://bitbucket.acensi.fr/scm/in/formation.git

php bin/console ckeditor:install
composer update
cd ..
cd ..
php bin/console doctrine:schema:update --force
php bin/console fos:user:create admin noname@acensi.fr root --super-admin

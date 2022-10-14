# https://www.e-vance.net/coding/moderner-wordpress-workflow-sage-trellis-bedrock

mkdir -p ~/Private/sites/muzyma.de
cd ~/Private/sites/muzyma.de


git clone --depth=1 git@github.com:roots/trellis.git ansible
rm -rf ansible/.git


cd ~/Private/sites/muzyma.de/ansible
ansible-galaxy install -r requirements.yml


cd ~/Private/sites/muzyma.de
git clone --depth=1 git@github.com:roots/bedrock.git site
rm -rf site/.git


cd ~/Private/sites/muzyma.de/site
composer install
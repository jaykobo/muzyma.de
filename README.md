# { ARCHIVED – 09/22 } Muzyma.de

This is a personal wordpress project for an portfolio-blogish homepage. Formerly hosted with DigitalOcean on [muzyma.de](http://muzyma.de/).

The project setup is based on [Trellis](https://roots.io/trellis/) and [Bedrock](https://roots.io/bedrock/) using this **Guide** [Moderner WordPress-Workflow (Sage, Trellis, Bedrock)](https://web.archive.org/web/20191023081421/https://www.e-vance.net/coding/moderner-wordpress-workflow-sage-trellis-bedrock) _(web.archive)_.

---

## Installation

Follow the Guide mentioned above. (Its very likely outdated)

## How to Update / Backup

### Updating Trellis

```
cd trellis

# Create a new git branch called 'upgrade'
git checkout -b upgrade

# Check out a flat-packed trellis repo into the upgrade/ directory
git clone --depth=1 https://github.com/roots/trellis.git upgrade

# Trash the git folder of the repo we just downloaded since
# we don't care about trellis's history, just our own app's history
rm -rf upgrade/.git

# Copy all files over from the upgrade directory to the
# trellis directory (which we're in)
rsync -ah --progress upgrade/ ./

# Remove the now-mostly-empty upgrade dir
rm -rf upgrade

# See what's changed
git status
```

_Source: https://discourse.roots.io/t/how-to-update-trellis/10248/8_

### Vagrant Backup & Import/Export Wordpress DB

```
1. vagrant up your development environment
2. vagrant ssh into the box
3. Navigate to the current folder cd /srv/www/example.com/current
4. Use WP-CLI to take a backup wp db export before-tld-change.sql
5. And use it to do the search/replace of the DB wp search-replace //example.dev //example.test (add --dry-run if you want to test it first)
6. Exit the SSH session
7. Edit trellis/group_vars/development/wordpress_sites.yml to use your new .test TLD
8. Run vagrant provision to re-build your development box. This will not effect your existing database or uploads.
9. Run vagrant up again to rewrite your /etc/hosts/ with the new domain
```

`$ wp db export meaningful-name-YYYY-MM-DD.sql`

_Source: https://discourse.roots.io/t/updating-development-from-dev-to-test-tld/12218_

## Other handy stuff

### .bash_profile / .zprofile

Commands to speed up your terminal

```
# muzyma.test start localhost server
# Exp.: type "muzyma up" to start webserver
function muzyma() {
    ( cd ~/Private/sites/muzyma/muzyma.de/trellis && vagrant $* )
}

# CD shortcut
alias cdmu-site='cd ~/Private/sites/muzyma/muzyma.de/site/'
alias cdmu-trel='cd ~/Private/sites/muzyma/muzyma.de/trellis/'
```

#! /usr/bin/env fish
# Builds the admin app and packages a clean distribution zip.
# Everything listed in .distignore is excluded, and the zip's top-level
# folder is "nitrospark" (the plugin slug), as WordPress.org expects.

set -l plugin_dir (dirname (realpath (status filename)))
cd $plugin_dir; or exit 1

npm run build; or exit 1
composer install --no-dev --optimize-autoloader; or exit 1

set -l tmp (mktemp -d)
set -l stage $tmp/nitrospark
mkdir -p $stage

rsync -a --exclude-from=.distignore ./ $stage/

set -l out (realpath ..)/nitrospark.zip
rm -f $out
cd $tmp
zip -y -r -q $out nitrospark
cd $plugin_dir
rm -rf $tmp

if test -e $out
    echo "Successfully created $out"
end

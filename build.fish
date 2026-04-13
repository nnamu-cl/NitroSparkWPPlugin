#! /usr/bin/env fish
npm run build

cd ..

zip -y -r nitrosparkwpplugin_build.zip NitroSparkWPPlugin/ \
  -x "NitroSparkWPPlugin/node_modules/*" \
  -x "NitroSparkWPPlugin/src/*" \
  -x "NitroSparkWPPlugin/package.json" \
  -x "NitroSparkWPPlugin/package-lock.json" \
  -x "NitroSparkWPPlugin/vite.admin.config.js" \
  -x "NitroSparkWPPlugin/jsconfig.json" \
  -x "NitroSparkWPPlugin/components.json" \
  -x "NitroSparkWPPlugin/start.sh" \
  -x "NitroSparkWPPlugin/REFERENCE_NITROWPINITIAL.md"


if test -e nitrosparkwpplugin_build.zip
    echo "Successfully created build in parent folder!"
end
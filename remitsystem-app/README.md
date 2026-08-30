# RemitSystem

---------- To Build APK -----------

### PROD

flutter build apk \
 --flavor production \
 -t lib/main_prod.dart \
 --dart-define=APP_FLAVOR=production

## DEV

flutter build apk \
 --flavor development \
 -t lib/main_dev.dart \
 --dart-define=APP_FLAVOR=development

---------- TO CREATE APP BUNDLE ------------

## PROD

flutter build appbundle \
 --flavor production \
 -t lib/main_prod.dart \
 --dart-define=APP_FLAVOR=production

## DEV

flutter build appbundle \
 --flavor development \
 -t lib/main_dev.dart \
 --dart-define=APP_FLAVOR=development

apcuまで入ってるイスコン的な環境なら

composer dump-autoload --ignore-platform-reqs --optimize-autoloader --no-dev --classmap-authoritative --apcu

> ignore-platform-reqs は、extをぬいたりしたときコケるので…

ふつうの？本番環境なら？

composer dump-autoload --optimize-autoloader --no-dev --classmap-authoritative


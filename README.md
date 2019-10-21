# apple_api
Основным классом для работы является ITunesService
Для корретной работы в конструктор нужно передать объект  GuzzleHttp\Client
Перед вызовом методов getArtistBySongId или getSongById нужно вызвать метод setHeaderAppleMusic и
передать ему необходимыt для авторизации заголовкb setHeaderAppleMusic(String $key, String $value).
Методы getSongById и getSongByIdRaw вторым параметром принимают Language Codes подробнее здесь
https://help.apple.com/itc/musicspec/?lang=en#/itc740f60829

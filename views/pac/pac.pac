// http://pac.itzmx.com

var SOCKS5 = "<?= $proxy ?>";

var domains = {
    "google.com": 1,
    <?php
    if(isset($domainList)){
        foreach($domainList AS $item){
            echo sprintf(',"%s": 1',$item['fdomain']);
        }
    }
    ?>
};

var direct = 'DIRECT;';

var hasOwnProperty = Object.hasOwnProperty;

function FindProxyForURL(url, host) {
    var suffix;
    var pos = host.lastIndexOf('.');
    while(1) {
        suffix = host.substring(pos + 1);
        if (hasOwnProperty.call(domains, suffix)) {
            return SOCKS5;
        }
        if (pos <= 0) {
            break;
        }
        pos = host.lastIndexOf('.', pos - 1);
    }
    return direct;
}
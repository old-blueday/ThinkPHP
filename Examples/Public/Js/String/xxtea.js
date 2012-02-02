/* xxtea.js
*
* Author:       Ma Bingyao <andot@ujn.edu.cn>
* Copyright:    CoolCode.CN
* Version:      1.21
* LastModified: 2006-06-17
* This library is free.  You can redistribute it and/or modify it.
* http://www.coolcode.cn/?p=128
*/
 
function long2str(v, w) {
    var vl = v.length;
    var sl = v[vl - 1] & 0xffffffff;
    for (var i = 0; i < vl; i++)
    {
        v[i] = String.fromCharCode(v[i] & 0xff,
                                   v[i] >>> 8 & 0xff,
                                   v[i] >>> 16 & 0xff, 
                                   v[i] >>> 24 & 0xff);
    }
    if (w) {
        return v.join('').substring(0, sl);
    }
    else {
        return v.join('');
    }
}
 
function str2long(s, w) {
    var len = s.length;
    var v = [];
    for (var i = 0; i < len; i += 4)
    {
        v[i >> 2] = s.charCodeAt(i)
                  | s.charCodeAt(i + 1) << 8
                  | s.charCodeAt(i + 2) << 16
                  | s.charCodeAt(i + 3) << 24;
    }
    if (w) {
        v[v.length] = len;
    }
    return v;
}
 
function xxtea_encrypt(str, key) {
    if (str == "") {
        return "";
    }
    var v = str2long(str, true);
    var k = str2long(key, false);
    var n = v.length - 1;
 
    var z = v[n], y = v[0], delta = 0x9E3779B9;
    var mx, e, q = Math.floor(6 + 52 / (n + 1)), sum = 0;
    while (0 < q--) {
        sum = sum + delta & 0xffffffff;
        e = sum >>> 2 & 3;
        for (var p = 0; p < n; p++) {
            y = v[p + 1];
            mx = (z >>> 5 ^ y << 2) + (y >>> 3 ^ z << 4) ^ (sum ^ y) + (k[p & 3 ^ e] ^ z);
            z = v[p] = v[p] + mx & 0xffffffff;
        }
        y = v[0];
        mx = (z >>> 5 ^ y << 2) + (y >>> 3 ^ z << 4) ^ (sum ^ y) + (k[p & 3 ^ e] ^ z);
        z = v[n] = v[n] + mx & 0xffffffff;
    }
 
    return long2str(v, false);
}
 
function xxtea_decrypt(str, key) {
    if (str == "") {
        return "";
    }
    var v = str2long(str, false);
    var k = str2long(key, false);
    var n = v.length - 1;
 
    var z = v[n - 1], y = v[0], delta = 0x9E3779B9;
    var mx, e, q = Math.floor(6 + 52 / (n + 1)), sum = q * delta & 0xffffffff;
    while (sum != 0) {
        e = sum >>> 2 & 3;
        for (var p = n; p > 0; p--) {
            z = v[p - 1];
            mx = (z >>> 5 ^ y << 2) + (y >>> 3 ^ z << 4) ^ (sum ^ y) + (k[p & 3 ^ e] ^ z);
            y = v[p] = v[p] - mx & 0xffffffff;
        }
        z = v[n];
        mx = (z >>> 5 ^ y << 2) + (y >>> 3 ^ z << 4) ^ (sum ^ y) + (k[p & 3 ^ e] ^ z);
        y = v[0] = v[0] - mx & 0xffffffff;
        sum = sum - delta & 0xffffffff;
    }
 
    return long2str(v, true);
}

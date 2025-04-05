#  LuxiQR: The Luxury QR Code Generator

Reinventing the wheel, one Quick Response Code at a time.

This is a project simply to learn how QR code generation works. There are a plethora of QR code generators in the wild,
so choose which one suits your needs best.

## Primary Objectives

- Understand how different data is encoded into a QR code
- Learn how to do polynomial math for Reed-Solomon error correction
  - _(This was a nightmare)_
- Implement settings customization for more control over the final generation
- Have various different output options for maximum integration compatibility

```php
$qrCode = new LuxiQR(data: "https://github.com/supergnaw/LuxiQR", eccLevel: "H");

echo $qrCode->outputTable();
```

## Various References

* [Barcode Contents](https://github.com/zxing/zxing/wiki/Barcode-Contents)
* [Thonky's QR Code Tutorial](https://www.thonky.com/qr-code-tutorial/)
* [How To Deploy a Static Site from GitHub with DigitalOcean App Platform](https://www.digitalocean.com/community/tutorials/how-to-deploy-a-static-site-from-github-with-digitalocean-app-platform-quickstart)
* [How to Create a QR Code Using JavaScript](https://www.turing.com/kb/creating-qr-code-using-js)
* [Let's develop a QR Code Generator](https://dev.to/maxart2501/series/13444)
* [Creating a QR Code step by step](https://www.nayuki.io/page/creating-a-qr-code-step-by-step)

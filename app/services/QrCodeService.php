<?

namespace App\Services;

// use the QRCode vendor package
use chillerlan\QRCode\QRCode;

class QrCodeService
{
    // take string data and create a qr code data string with it
    static function Create($data)
    {
        return (new QRCode)->render($data);
    }

    // create an HTML QR code image
    static function CreateHtmlPreviewImage($data)
    {
        return '<img style="max-width: 500px;" src="' . self::Create($data) . '" alt="QR Code" />';
    }
}

<?php
/**
 * Image Safe Library
 *
 * Image safe is used to ensure that a file is in fact an image.
 * Image safe can also perform basic scans on the image to ensure no hidden nasties.
 *
 * @author sammottley@gmail.com (Sam Mottley)
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/motters/image-safe
 *
 */
require_once '../vendor/autoload.php';
use Motters\ImageSafe\ImageSafe;
use Motters\ImageSafe\Exceptions\ExecutionException;

if(isset($_FILES["image"]))
{
    // Start image safe validation
    $v = new ImageSafe();

    // Set the rules for image safe (not all required)
    $validationRules = [
        // Types of mimes that are allowed (Common types are allowed by default)
        'Mimes', // Optional settings: ['allowed'=>['image/jpeg; charset=binary', 'image/jpg; charset=binary']]

        // Make sure file name is alpha numerical (Files should always be renamed)
        'FileName', // Optional settings: ['allowed'=>['-','_'], 'maximumCharacters'=>'100', 'minimumCharacters'=>'1']

        // Minimum and maximum size of file image (Minimum file size will help stop denial of service attacks)
        'FileSize', // Optional settings: ['maximum'=>'2097152', 'minimum'=>'10240']

        // Make sure the image has some valid and set dimensions
        'DimensionSize', // Optional settings: ['height'=>'1024', 'width'=>'768']

        // Searches for elements in the images mimes that could potential be arbitrary code, this is very beta!
        'Characters', // Optional settings: ['unbanCharacters'=>['<?'], 'addbanCharacters'=>['<?'], 'addremoveCharacters'=>['<?'], 'unremoveCharacters'=>['<?']]

        // Makes sure that the WHOLE image extension meets the below allowed white list
        'FileExtension', // Optional settings: ['allowed'=>['jpeg','jpeg.php']]
    ];

    try {
        // Has validation passed or failed
        if( $v->validate( $_FILES["image"], $validationRules ) )
        {
            // Now move / proccess the image file
            die('File is an image and is read to uploads');
        }
    } catch (ExecutionException $ex) {
        echo $ex->getMessage();
    }

    // Failed show some error message
    die('validation failed');
}

echo '  <form method="post" enctype="multipart/form-data">
            Select image to verify:
            <input type="file" name="image" id="image"><br/><br/>
            <input type="submit" value="Test" name="submit">
        </form>';
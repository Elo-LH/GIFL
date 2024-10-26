<?php

class Uploader
{
    private string $uploadFolder = "uploads";
    private RandomStringGenerator $gen;

    public function __construct()
    {
        $this->gen = new RandomStringGenerator();
    }

    /**
     * @param array $files your $_FILES superglobal
     * @param string $uploadField the name of of the type="file" input
     *
     */
    public function upload(array $files, string $uploadField): ?Gif
    {
        if (isset($files[$uploadField])) {
            try {
                $file_name = $files[$uploadField]['name'];
                $file_tmp = $files[$uploadField]['tmp_name'];

                // Check if the file was uploaded successfully
                if (!is_uploaded_file($file_tmp)) {
                    throw new Exception("File upload failed.");
                }

                $tabFileName = explode('.', $file_name);
                $file_ext = strtolower(end($tabFileName));

                $newFileName = $this->gen->generate(8);

                // check the MIME type
                $mimeType = mime_content_type($file_tmp);
                if ($mimeType != "image/gif") {
                    throw new Exception("Bad file extension. Please upload a GIF file.");
                }
                if ($file_ext != "gif") {
                    throw new Exception("Bad file extension. Please upload a GIF file.");
                } else {
                    $url = $this->uploadFolder . "/" . $newFileName . "." . $file_ext;
                    move_uploaded_file($file_tmp, $url);
                    $um = new UserManager;
                    $user = $um->findById($_SESSION['id']);
                    return new Gif($url, $user);
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                return null;
            }
        }
        return null;
    }
}

<?php
namespace Viking\MainBundle\Form;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DocumentForm
{

    public $id;

    public $name;

    public $path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/articles';
    }

    public function upload()
    {
        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->file) {
            return;
        }
        $this->file->move($this->getUploadRootDir(), $this->name.'.'.$this->file->guessExtension());
        // « nettoie » la propriété « file » comme vous n'en aurez plus besoin
        $this->file = null;
    }

    public function getJson()
    {
        $files   = array();
        $rootDir = realpath(__DIR__.'/../../../');
        $finder  = \Symfony\Component\Finder\Finder::create()->files()->name('/\.(?:gif|png|jpg|jpeg)$/i');
        foreach ($finder->in($this->getUploadRootDir()) as $img) {
            /** @var $img \Symfony\Component\Finder\SplFileInfo */
            $files[] = array(
                'thumb'  => substr($img->getRealPath(), strlen(realpath($rootDir))),
                'image'  => substr($img->getRealPath(), strlen(realpath($rootDir))),
                'title'  => pathinfo($img->getRealPath(), PATHINFO_FILENAME),
                'folder' => pathinfo($this->getUploadRootDir(), PATHINFO_BASENAME)
            );
        }
        $handle = fopen($this->getUploadRootDir().'/images.json', 'w');
        fwrite($handle, json_encode($files));
        fclose($handle);
    }
}
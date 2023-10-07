<?php

declare(strict_types=1);

class Uploader extends SplFileInfo implements UploaderInterface
{
    private const ERROR_MESSGAES = [
        UPLOAD_ERR_OK => 'There is no error, the file uploaded with success.',
        UPLOAD_ERR_INI_SIZE => 'The file "%s" exceeds the upload_max_filesize directive in php.ini.',
        UPLOAD_ERR_FORM_SIZE => 'The file "%s" exceeds the upload limit in your form.',
        UPLOAD_ERR_PARTIAL => 'The file "%s" was only partially uploaded.',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
        UPLOAD_ERR_CANT_WRITE => 'The file "%s" could not be written on the disk',
        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
        -1 => 'The file "%s" was not upload due to an unknown error.',
    ];
    private ?string $originalName;
    private ?string $mimeType;
    private int $errorCode;
    private FilesSystemInterface $fileSyst;
    private FilesManagerInterface $fm;

    public function __construct(?string $path, ?string $originalName, ?string $mimeType, int|null $errorCode = null, FilesSystemInterface $fileSyst, FilesManagerInterface $fm)
    {
        $this->originalName = $originalName;
        $this->mimeType = $mimeType ?: 'application/octet-stram';
        $this->errorCode = $errorCode ?: UPLOAD_ERR_OK;
        if ($this->errorCode === UPLOAD_ERR_OK && !is_file($path)) {
            throw new FileNotFoundException($path);
        }
        $this->fileSyst = $fileSyst;
        $this->fm = $fm;
        $this->path = $path;
        parent::__construct($path);
    }

    public function saveFilex()
    {
        $targetFilePath = $this->fm->getDestinationPath();
        $fileName = $targetFilePath . DS . $this->fm->getFileName();
        if ($this->fileSytem->createDir($this->fm->getTargetDir() . DS . $targetFilePath)) {
            if (!file_exists($this->fm->getTargetDir() . DS . $fileName)) {
                if (move_uploaded_file($this->fm->getSourcePath() . DS . $this->fm->getFileName(), $this->fm->getTargetDir() . DS . $fileName)) {
                    if (!file_exists(IMAGE_ROOT_SRC . $targetFilePath) && !in_array($this->fm->getDestinationPath(), ['posts'])) {
                        copy($this->fm->getTargetDir() . DS . $fileName, IMAGE_ROOT_SRC . $fileName);
                    }
                    return $fileName;
                }
                return 'error';
            }
            return  $fileName;
        }
    }

    public function upload(): string
    {
        if ($this->fm->validate()) {
            $en = $this->model->getEntity();
            if ($en->isInitialized($en->getColId('media'))) {
                $incommingPath = $this->fm->getDestinationPath() . DS . $this->fm->getFileName();
                if (file_exists($incommingPath)) {
                    if ($incommingPath == $en->{$en->getColId('media')}) {
                        return $this->model;
                    } else {
                        $setter = $en->getSetter($en->getColId('media'));
                        $this->model->getEntity()->{$setter}(serialize([$incommingPath]));
                        return $this->model;
                    }
                }
            }
            $path = $this->saveFile('xxx');
            if ($path === 'error') {
                throw new UnableToSaveFileOnDiskException('Unable to save file on disk! Please contact the administrator!');
            }
            return $path;
        }
    }

    public function saveFile(string $directory, string|null $newName = null) : bool
    {
        if (!$this->isValid()) {
            throw new FilesException($this->getErrorMessage());
        }
        $directory = $this->fileSyst->checkWritable($directory, true);
        $this->fileSyst->createDirectory($directory);
        $target = rtrim($directory, DS) . DS . ($newName ?? $this->originalName);
        $error = '';
        if (!file_exists($target)) {
            set_error_handler(function ($type, $msg) use (&$error) {
                $error = $msg;
            });
            $fileMoved = move_uploaded_file($this->getPathname(), $target);
            restore_error_handler();
            if (!$fileMoved) {
                throw new FilesException(sprintf('Could not move file "%s " to "%s "-->"%s".', $this->getPathname(), $target, strip_tags($error)));
            }
            @chmod($target, 0666 & ~umask());
        }
        $this->syncSrcLocation($target);
        return true;
    }

    public function syncSrcLocation(string $file) : void
    {
        $targetFilePath = basename(dirname($file)) . DS . basename($file);
        if (!file_exists(IMAGE_ROOT_SRC . $targetFilePath)) {
            copy($file, IMAGE_ROOT_SRC . $targetFilePath);
        }
    }

    public function getErrorMessage() : ?string
    {
        if ($this->isValid()) {
            return null;
        }
        $maxFileSize = $this->errorCode === UPLOAD_ERR_INI_SIZE ? FileInfoUtils::getMaxFileSize() : 0;
        return sprintf(self::ERROR_MESSGAES[$this->getErrorCode()] ?: self::ERROR_MESSGAES[-1], $this->originalName, $maxFileSize);
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): self
    {
        $this->originalName = $originalName;
        return $this;
    }

    public function getExtension(): string
    {
        return pathinfo($this->originalName, PATHINFO_EXTENSION);
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    public function getErrorCode(): int|null
    {
        return !$this->isValid() ? $this->errorCode : null;
    }

    public function setErrorCode(int $errorCode): self
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    public function isValid() : bool
    {
        return $this->errorCode === UPLOAD_ERR_OK && is_uploaded_file($this->getPathname());
    }
}

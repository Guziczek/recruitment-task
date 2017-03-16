<?php
declare(strict_types = 1);

namespace Bajcik\IO;

use Bajcik\Util\Objects;
use Bajcik\Util\StringUtils;
use Bajcik\Util\SystemUtils;

/**
 * Represent file path in the system.
 * Value object.
 *
 * @author Paweł Cichorowski <pawel.cichorowski@gmail.com>
 *
 */
final class File
{

    private $path;
    private $cacheInfo = null;

    /**
     *
     * @param self|string $path
     * @param self|string|null $subPath
     *
     * @return self
     */
    public static function of($path, $subPath = null): self
    {
        if ($path instanceof File) {
            $path1 = $path->getAbsolutePath();
        } else if (is_string($path)) {
            $path1 = $path;
        } else {
            // TODO exception
            throw new \InvalidArgumentException();
        }

        if (null === $subPath) {
            $path2 = null;
        } else if ($subPath instanceof File) {
            $path2 = $subPath->getAbsolutePath();
        } else if (is_string($subPath)) {
            $path2 = $subPath;
        } else {
            // TODO exception
            throw new \InvalidArgumentException();
        }

        if (null !== $path2) {
            $resultPath = $path1 . DIRECTORY_SEPARATOR . $path2;
        } else {
            $resultPath = $path1;
        }

        return new File($resultPath);
    }

    /**
     *
     * @param string $path
     */
    private function __construct(string $path)
    {
        $this->path = FileUtils::normalize($path);
    }

    /**
     *
     * @return string
     */
    public function getName(): string
    {
        $this->ensureInfoParsed();

        return isset($this->cacheInfo['basename']) ? $this->cacheInfo['basename'] : '';
    }

    /**
     *
     * @return self
     */
    public function getDirectory(): self
    {
        $this->ensureInfoParsed();

        return File::of(isset($this->cacheInfo['dirname']) ? $this->cacheInfo['dirname'] : '');
    }

    /**
     *
     * @return string
     */
    public function getExtension(): string
    {
        $this->ensureInfoParsed();

        return isset($this->cacheInfo['extension']) ? $this->cacheInfo['extension'] : '';
    }

    private function ensureInfoParsed()
    {
        if (null !== $this->cacheInfo) {
            return;
        }

        $this->cacheInfo = pathinfo($this->getAbsolutePath());
    }

    public function exists(): bool
    {
        return file_exists($this->getAbsolutePath());
    }

    public function getSize(): int
    {
        $result = filesize($this->getAbsolutePath());
        if (false === $result) {
            throw new IOException("Could not determine file size: {$this->getUtfAbsolutePath()}");
        }

        return $result;
    }

    public function makeDirs()
    {
        if (false === \mkdir($this->path, 0777, true)) {
            throw new IOException("Could not create directory: {{$this->getUtfAbsolutePath()}}");
        }
    }

    public function truncate($size = 0)
    {
        if ($size < 0) {
            throw new \InvalidArgumentException('Invalid size: ' . $size);
        }
        $handle = @fopen($this->getAbsolutePath(), 'r+');
        if (false === $handle) {
            throw new IOException("Could not open file: {$this->getUtfAbsolutePath()}");
        }
        $result = ftruncate($handle, $size);
        fclose($handle);
        if (false === $result) {
            throw new IOException("Could not truncate file: {$this->getUtfAbsolutePath()}");
        }

        return $this;
    }

    public function isReadable(): bool
    {
        if ($this->isFile()) {
            $fp = @fopen($this->getAbsolutePath(), 'r');
            if (false !== $fp) {
                fclose($fp);

                return true;
            } else {
                return false;
            }
        } else {
            return is_readable($this->getAbsolutePath());
        }
    }

    public function isWritable(): bool
    {
        if ($this->isFile()) {
            $fp = @fopen($this->getAbsolutePath(), 'a');
            if (false !== $fp) {
                fclose($fp);

                return true;
            } else {
                return false;
            }
        } else {
            return is_writable($this->getAbsolutePath());
        }
    }

    public function getAbsolutePath(): string
    {
        return $this->path;
    }

    public function delete()
    {
        if (!$this->exists()) {
            return false;
        }

        return unlink($this->getAbsolutePath());
    }

    public function deleteOnScriptExit()
    {
        $self = $this;
        register_shutdown_function(function () use ($self) {
            if ($self->exists()) {
                $self->delete();
            }
        });
    }

    public function isDirectory(): bool
    {
        return is_dir($this->getAbsolutePath());
    }

    public function isFile(): bool
    {
        return is_file($this->getAbsolutePath());
    }

    public function isHidden(): bool
    {
        if (SystemUtils::isWindowsOperatingSystem()) {
            $attr = trim(exec("FOR %A IN (\"{$this->path}\") DO @ECHO %~aA"));

            return ($attr[3] === 'h');
        } else {
            return StringUtils::startsWith($this->getName(), '.');
        }
    }

    /**
     *
     * @param self|string $subpath
     *
     * @return self
     */
    public function appendPath($subpath): self
    {
        Objects::requireNonNull($subpath);

        return File::of($this, $subpath);
    }

    /**
     *
     * @param self|string $nameSuffix
     *
     * @return self
     */
    public function appendName($nameSuffix): self
    {
        Objects::requireNonNull($nameSuffix);

        return File::of($this->getAbsolutePath() . $nameSuffix);
    }

    /**
     *
     * @param self|string $subpath
     *
     * @return self
     */
    public function prependPath($subpath): self
    {
        Objects::requireNonNull($subpath);

        return File::of($subpath, $this);
    }

    /**
     *
     * @param File $directory
     *
     * @return string
     */
    public function getRelativePathTo(File $directory): string
    {
        Objects::requireNonNull($directory);

        $prefix = $directory->getAbsolutePath();
        $path = $this->getAbsolutePath();

        if (substr($path, 0, strlen($prefix)) == $prefix) {
            $path = substr($path, strlen($prefix));
        }

        if (StringUtils::startsWith($path, DIRECTORY_SEPARATOR)) {
            return \substr($path, 1);
        }

        return $path;
    }

    /**
     *
     * @throws FileNotFoundException
     * @throws IOException
     * @return string
     */
    public function getContents(): string
    {
        if (!$this->exists()) {
            throw new FileNotFoundException("File does not exists: {$this->getUtfAbsolutePath()}");
        }
        $result = file_get_contents($this->getAbsolutePath());
        if (false === $result) {
            throw new IOException("Cannot read file contents: {$this->getUtfAbsolutePath()}");
        }

        return $result;
    }

    /**
     * @param string $contents
     * @throws IOException
     */
    public function setContents(string $contents)
    {
        $result = file_put_contents($this->getAbsolutePath(), $contents);
        if (false === $result) {
            throw new IOException("Cannot write contents to file: {$this->getUtfAbsolutePath()}");
        }
    }

    /**
     *
     * @param File $destinationPath
     *
     * @throws IOException
     */
    public function copyTo(File $destinationPath)
    {
        Objects::requireNonNull($destinationPath);

        if (!$this->exists()) {
            throw new IOException("Could not copy, source file not exists. Copy from '{$this->getUtfAbsolutePath()}' to '{$destinationPath->getUtfAbsolutePath()}'");
        }

        // if (!$destinationPath->getDirectory()) {
        // throw new IOException("Could not copy, source file not exists. Copy from '{$this->getUtfAbsolutePath()}' to '{$destinationPath->getUtfAbsolutePath()}'");
        // }

        if (false === @\copy($this->getAbsolutePath(), $destinationPath->getAbsolutePath())) {
            throw new IOException("Could not copy file from '{$this->getUtfAbsolutePath()}' to '{$destinationPath->getUtfAbsolutePath()}'");
        }
    }

    /**
     *
     * @param File $destinationPath
     *
     * @throws IOException
     */
    public function renameTo(File $destinationPath)
    {
        Objects::requireNonNull($destinationPath);

        if (false === @\rename($this->getAbsolutePath(), $destinationPath->getAbsolutePath())) {
            throw new IOException("Could not move/rename file from '{$this->getUtfAbsolutePath()}' to '{$destinationPath->getUtfAbsolutePath()}'");
        }
    }

    public function __toString()
    {
        return $this->getUtfAbsolutePath();
    }

    public function getUtfAbsolutePath(): string
    {
        if (SystemUtils::isWindowsOperatingSystem()) {
            return iconv('Windows-1250', 'UTF-8', $this->path);
        }

        return $this->path;
    }

    /**
     *
     * @param File $anotherFile
     *
     * @return boolean
     */
    public function equals(File $anotherFile): bool
    {
        Objects::requireNonNull($anotherFile);

        $path1 = \realpath($this->getAbsolutePath());
        $path2 = \realpath($anotherFile->getAbsolutePath());
        if ((false === $path1) || (false === $path2)) {
            return $path1 === $path2;
        }

        return $this->getAbsolutePath() === $anotherFile->getAbsolutePath();
    }

    /**
     * @throws IOException
     * @return string[]
     */
    public function getLinesIterator()
    {
        if (!$this->exists()) {
            throw new FileNotFoundException("File does not exists: {$this->getUtfAbsolutePath()}");
        }

        $fp = fopen($this->getAbsolutePath(), 'r');
        if (false == $fp) {
            throw new IOException("Cannot read file contents: {$this->getUtfAbsolutePath()}");
        }

        try {
            while (false !== ($line = fgets($fp))) {
                yield $line;
            }
        } finally {
            fclose($fp);
        }
    }

    public function isAbsolute(): bool
    {
        //TODO dirty check
        $path = $this->getAbsolutePath();

        return StringUtils::startsWith($path, '/') || StringUtils::containsIgnoreCase($path, ':');
    }

}

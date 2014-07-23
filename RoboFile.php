<?php
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class RoboFile extends \Robo\Tasks
{
    // define public methods as commands

    protected $chmodRead = 0774;
    protected $chmodWrite = 0774;

    protected $chownBackupUser = 'root';
    protected $chownBackupGroup = 'root';
    protected $chownWebappUser = 'www-data';
    protected $chownWebappGroup = 'www-data';

    protected $dirCurrent = '.';

    protected $dirDeployRoot = '/var/www/arinoid-prod';
    protected $dirDeploySymLink = '/var/www/arinoid';

    protected $dirBackup = '/var/arinoid_backups';

    protected $date = '';

    protected $dirDeploy = '';

    protected $dirs = [
        'backend',
        'common',
        'console',
        'frontend',
        'vendor',
    ];

    public function __construct()
    {
        $this->date = date('Y-m-d_H-i');
        $this->dirDeploy = "{$this->dirDeployRoot}/{$this->date}";
    }

    public function run()
    {
        $this->taskBackup();
        $this->taskInit();
        $this->taskCopyFiles();
        $this->taskSetRights();
        $this->taskSetSymLink();
        $this->taskBuildNotes();

        $this->say('Done.');
    }

    protected function taskBackup()
    {
        $this->printTaskInfo('Making Backup...');

        $this->taskFileSystemStack()
            ->mkdir($this->dirBackup)
            ->run();

        $files = Finder::create()->files()->name('current.txt')->in($this->dirDeployRoot);

        $last = NULL;
        /** @var $file SplFileInfo */
        foreach ($files as $file) {
            $last = $file->getContents();
        }

        $this->taskExecStack()
            ->exec("cd {$last}")
            ->exec("zip -r {$this->dirBackup}/{$this->date}_arinoid.zip *")
            ->run();
    }

    protected function taskInit()
    {
        $this->printTaskInfo('Making Init...');

        $this->taskComposerUpdate()->run();

        $this->taskExec('php yii migrate --interactive=0')->run();
    }

    protected function taskCopyFiles()
    {
        $this->printTaskInfo('Copying files...');

        $this->taskFileSystemStack()
            ->mkdir($this->dirDeploy)
            ->run();

        $dirs = [];
        foreach ($this->dirs as $dir) {
            $dirs["{$this->dirCurrent}/{$dir}"] = "{$this->dirDeploy}/{$dir}";
        }

        $this->taskCopyDir($dirs)
            ->run();
    }

    protected function taskSetRights()
    {
        $this->printTaskInfo('Setting Rights...');

        $this->taskFileSystemStack()
            ->chmod($this->dirDeploy, $this->chmodRead)
            ->chown($this->dirDeploy, $this->chownWebappUser, true)
            ->chgrp($this->dirDeploy, $this->chownWebappGroup, true)
            ->run();
    }

    protected function taskSetSymLink()
    {
        $this->printTaskInfo('Setting SymLinks...');

        $this->taskFileSystemStack()
            ->symlink($this->dirDeploy, $this->dirDeploySymLink)
            ->run();

        $this->taskWriteToFile("{$this->dirDeployRoot}/current.txt")
            ->line($this->dirDeploy)
            ->run();
    }

    protected function taskBuildNotes()
    {
        $this->printTaskInfo('Setting BuildNotes...');

        $gitCommitHash = $this->taskExec("git log -1 --format='%H'")
            ->run()->getMessage();

        $this->taskWriteToFile("{$this->dirDeploy}/buildNotes.txt")
            ->line("BuildNotes:")
            ->line("Date: {$this->date}")
            ->line("Deploy dir: {$this->dirDeploy}")
            ->line("Backup dir: {$this->dirBackup}")
            ->line("GIT Commit Hash: {$gitCommitHash}")
            ->run();
    }

}
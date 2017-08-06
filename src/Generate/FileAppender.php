<?php namespace Brackets\AdminGenerator\Generate;

use Brackets\AdminGenerator\Generate\Traits\Helpers;
use Brackets\AdminGenerator\Generate\Traits\Names;
use Brackets\AdminGenerator\Generate\Traits\Columns;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class FileAppender extends Command {

    use Helpers, Columns, Names;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * Relations
     *
     * @var string
     */
    protected $relations = [];

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    protected function getArguments() {
        return [
            ['table_name', InputArgument::REQUIRED, 'Name of the existing table'],
        ];
    }

    /**
     * Determine if the content is already present in the file
     *
     * @param $path
     * @param $content
     * @return bool
     */
    protected function alreadyAppended($path, $content)
    {
        if (strpos($this->files->get($path), $content) !== false) {
            return true;
        }
        return false;
    }

    /**
     * Append content to file only if if the content is not present in the file
     *
     * @param $path
     * @param $content
     */
    protected function appendIfNotAlreadyAppended($path, $content)
    {
        if (!$this->files->exists($path)) {
            $this->makeDirectory($path);
            $this->files->put($path, "<?php\n\n".$content);
        } else if (!$this->alreadyAppended($path, $content)) {
            $this->files->append($path, $content);
        }
    }

    /**
     * Execute the console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initCommonNames($this->argument('table_name'), $this->option('model-name'), $this->option('controller-name'));

        $output = parent::execute($input, $output);

        return $output;
    }
}
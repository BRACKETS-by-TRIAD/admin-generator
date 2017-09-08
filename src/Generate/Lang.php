<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class Lang extends FileAppender {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:lang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Append admin translations into a admin lang file';

    /**
     * Path for view
     *
     * @var string
     */
    protected $view = 'lang';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        //TODO check if exists
//        //TODO make global for all generator
//        //TODO also with prefix
        if(!empty($template = $this->option('template'))) {
            $this->view = 'templates.'.$template.'.lang';
        }

        if(empty($locale = $this->option('locale'))) {
            $locale = 'en';
        }

        if(!empty($belongsToMany = $this->option('belongs-to-many'))) {
            $this->setBelongToManyRelation($belongsToMany);
        }

        // TODO what if a file has been changed? this will append it again (because the content is not present anymore -> we should probably check only for a root key for existence)

        // TODO name-spaced model names should be probably inserted as a sub-array in a translation file..

        if ($this->replaceIfNotPresent(resource_path('lang/'.$locale.'/admin.php'),  "// Do not delete me :) I'm used for auto-generation\n",$this->buildClass()."\n", "<?php\n\nreturn [\n    // Do not delete me :) I'm used for auto-generation\n];")){
            $this->info('Appending translations finished');
        }
    }

    protected function buildClass() {

        return view('brackets/admin-generator::'.$this->view, [
            'modelLangFormat' => $this->modelLangFormat,
            'modelBaseName' => $this->modelBaseName,
            'modelPlural' => $this->modelPlural,

            'columns' => $this->getVisibleColumns($this->tableName, $this->modelVariableName),
            'relations' => $this->relations,
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a controller for the given model'],
            ['locale', 'c', InputOption::VALUE_OPTIONAL, 'Specify custom locale'],
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom template'],
            ['belongs-to-many', 'btm', InputOption::VALUE_OPTIONAL, 'Specify belongs to many relations'],
        ];
    }

}
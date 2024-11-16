<?php
// cette classe va nous permettre de gérer notre plugin

namespace StMartinWof;

class Plugin
{

    protected static $instance;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var CustomPostType[]
     */
    protected $customTypes = [];

    /**
     * @var CustomTaxonomy[]
     */
    protected $customTaxonomies = [];

    /**
     * @var PostMetadata[]
     */
    protected $postMetadatas = [];


    protected $roles = [];

    protected $routes = [
        'wof/home/?$'
    ];


    public function __construct()
    {

        $this->registerRouter();

        /*
         * On diffère l'exécution afin de s'assurer que toutes les
         * traductions, post types, taxonomies, etc., sont correctement
         * initialisés après le chargement du coeur de WordPress.
         * Comme le chargement des langues est sur le hook 'init' en priorité 1 on met 5 pour que ça arrive après.
         * Mais on met tout cela avant la priorité 10 sinon le CustomPostType n'apparaît pas dans le menu admin
         */
        add_action('init', [$this, 'registerAllCustomPostTypes'], 5);
        add_action('init', [$this, 'registerAllPostTypeCustomMetaboxes'], 5);

        add_action('init', [$this, 'registerAllCustomTaxonomies'], 5);
        add_action('init', [$this, 'registerAllTaxonomyCustomMetadatas'], 5);

        add_action('init', [$this, 'registerAllCustomRoles'], 5);

        add_action('init', [$this, 'registerAllUserMetadatas'], 5);


       /* $this->registerAllCustomPostTypes();
        $this->registerAllPostTypeCustomMetaboxes();
        $this->registerAllCustomTaxonomies();
        $this->registerAllTaxonomyCustomMetadatas();
        $this->registerAllCustomRoles();
        $this->registerAllUserMetadatas();*/


        add_filter('init', [$this, 'flushRoutes'], 20);
    }

    public static function getInstance()
    {
        if(static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function registerAllUserMetadatas() {}
    public function registerAllCustomPostTypes() {}
    public function registerAllPostTypeCustomMetaboxes() {}
    public function registerAllCustomTaxonomies() {}
    public function registerAllTaxonomyCustomMetadatas() {}
    public function registerAllCustomRoles() {}



    //===============================================================================

    protected function registerCustomPostType($keyName, $label, $class = CustomPostType::class)
    {
        $customType = new $class($keyName, $label);
        $customType->register();
        $this->customTypes[$keyName] = $customType;

        return $customType;
    }


    protected function registerCustomTaxonomy($keyName, $label, array $postTypes, $class = CustomTaxonomy::class)
    {
        $customTaxonomy = new $class($keyName, $label, $postTypes);
        $customTaxonomy->register();
        $this->customTaxonomies[$keyName] = $customTaxonomy;
        return $customTaxonomy;
    }

    /**
     * @param string $name
     * @param string $label
     * @param string $postType
     * @param string $class
     * @return PostTypeCustomMetadata
     */
    protected function registerPostTypeCustomMetabox($keyName, $label, $postType, $class = PostTypeCustomMetabox::class)
    {
        $postMetadata = new $class(
            $keyName, // l'identifiant (la variable) qui va nous nous permettre de stocker l'information
            $label, // libellé
            $postType // le custom post type  sur lequel ajouter le champs supplémentaire
        );
        /* Fire our meta box setup function on the post editor screen. */
        // on peut aussi juste faire : $postMetadata->register();
        add_action( 'load-post.php', [$postMetadata, 'register'] );
        add_action( 'load-post-new.php', [$postMetadata, 'register'] );
        
        $this->postMetadatas[$keyName] = $postMetadata;
        return $postMetadata;
    }
    

    /**
     * @param string $name
     * @param string $label
     * @param string $postType
     * @param string $class
     * @return PostTypeCustomMetadata
     */
    protected function registerPostTypeCustomMetadata($keyName, $label, $postType, $class = PostTypeCustomMetadata::class)
    {
        $postMetadata = new $class(
            $keyName, // l'identifiant (la variable) qui va nous nous permettre de stocker l'information
            $label, // libellé
            $postType // le custom post type  sur lequel ajouter le champs supplémentaire
        );
        $postMetadata->register();
        $this->postMetadatas[$keyName] = $postMetadata;
        return $postMetadata;
    }

    protected function registerCustomRole($keyName, $label, $class = CustomRole::class)
    {
        $role = new $class($keyName, $label);
        $role->register();
        $this->roles[] = $role;
        return $role;
    }



    //===============================================================================

    public function registerRouter()
    {
        $this->router = new \StMartinWof\Router();
        $this->router->register();
        $this->registerRoutes();
    }


    public function registerRoutes()
    {
        foreach($this->routes as $url) {
            $this->addRoute($url);
        }
    }


    public function addRoute($url)
    {
        $this->router->addRoute($url);
        return $this;
    }

    public function getRouter()
    {
        return $this->router;
    }




    //===============================================================================
    // méthodes utilitaires
    //===============================================================================

    // appelé lorsque le plugin est désactivé
    public function deactivate()
    {
        $this->flushRoutes();
    }


    // appelé lorsque le plugin est activé
    public function activate()
    {

    }

    // appelé lors de la désinstallation du plugin ⚠️ Attention cette méthode doit être statique (obligation wordpress)
    public static function uninstall()
    {

    }

    public function flushRoutes()
    {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    //===========================================================
    public function register()
    {
        // https://developer.wordpress.org/reference/functions/register_activation_hook/
        // enregistrement du hook qui se déclenche au moment de l'activation du plugin
        // lorsque le plugin sera activé, wp appelera la méthode activate() de l'objet $plugin (syntaxe "callable")

        register_activation_hook(__FILE__ . '/..', [$this, 'activate']);
        register_deactivation_hook(__FILE__ . '/..', [$this, 'deactivate']);
        register_uninstall_hook(__FILE__ . '/..', [static::class, 'uninstall']);
    }

}

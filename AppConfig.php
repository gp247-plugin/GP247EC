<?php
/**
 * Template format 1.0
 */
#App\GP247\Templates\GP247EC\AppConfig.php
namespace App\GP247\Templates\GP247EC;

use GP247\Core\Models\AdminConfig;
use GP247\Core\Models\AdminStore;
use GP247\Core\Models\AdminHome;
use GP247\Front\Models\FrontLayoutBlock;
use GP247\Front\Models\FrontBanner;
use GP247\Core\ExtensionConfigDefault;
class AppConfig extends ExtensionConfigDefault
{
    public function __construct()
    { 
        //Read config from gp247.json
        $config = file_get_contents(__DIR__.'/gp247.json');
        $config = json_decode($config, true);
    	$this->configGroup = $config['configGroup'];
        $this->configKey = $config['configKey'];
        $this->configCode = $config['configCode'] ?? $this->configKey;
        $this->requireCore = $config['requireCore'] ?? [];
        $this->requireComposerPackages = $config['requireComposerPackages'] ?? [];
        $this->requireGp247Extensions = $config['requireGp247Extensions'] ?? [];

        //Path
        $this->appPath = $this->configGroup . '/' . $this->configKey;
        //Language
        $this->title = trans($this->appPath.'::lang.title');
        //Image logo or thumb
        $this->image = $this->appPath.'/'.$config['image'];
        //
        $this->version = $config['version'];
        $this->auth = $config['auth'];
        $this->link = $config['link'];
    }

    public function install()
    {
        $check = AdminConfig::where('key', $this->configKey)
            ->where('group', $this->configGroup)->first();
        if ($check) {
            //Check Plugin key exist
            $return = ['error' => 1, 'msg' =>  gp247_language_render('admin.extension.plugin_exist')];
        } else {
            //Insert plugin to config
            $dataInsert = [
                [
                    'group'  => $this->configGroup,
                    'code'    => $this->configCode,
                    'key'    => $this->configKey,
                    'sort'   => 0,
                    'store_id' => GP247_STORE_ID_GLOBAL,
                    'value'  => self::ON, //Enable extension
                    'detail' => $this->appPath.'::lang.title',
                ],
            ];

            try {
                AdminConfig::insert(
                    $dataInsert
                );
                $return = ['error' => 0, 'msg' => gp247_language_render('admin.extension.install_success')];
            } catch (\Throwable $e) {
                $return = ['error' => 1, 'msg' => $e->getMessage()];
            }
        }

        //Setup store
        $this->setupStore();

        return $return;
    }

    public function uninstall()
    {
        $return = ['error' => 0, 'msg' => ''];
        //Please delete all values inserted in the installation step
        try {
            (new AdminConfig)
            ->where('key', $this->configKey)
            ->orWhere('code', $this->configKey.'_config')
            ->delete();

            //Admin config home
            AdminHome::where('extension', $this->appPath)->delete();

            $return = ['error' => 0, 'msg' => gp247_language_render('admin.extension.uninstall_success')];
        } catch (\Throwable $e) {
            $return = ['error' => 1, 'msg' => $e->getMessage()];
        }

        //Remove setup for all stores
        $this->removeStore();

        return $return;
    }
    
    public function enable()
    {
        $return = ['error' => 0, 'msg' => ''];
        $process = (new AdminConfig)
            ->where('group', $this->configGroup)
            ->where('key', $this->configKey)
            ->update(['value' => self::ON]);
            
        //Admin config home
        AdminHome::where('extension', $this->appPath)->update(['status' => 1]);

        if (!$process) {
            $return = ['error' => 1, 'msg' => gp247_language_render('admin.extension.action_error', ['action' => 'Enable'])];
        }
        $return = ['error' => 0, 'msg' => gp247_language_render('admin.extension.enable_success')];
        return $return;
    }

    public function disable()
    {
        $return = ['error' => 0, 'msg' => ''];
        $process = (new AdminConfig)
            ->where('group', $this->configGroup)
            ->where('key', $this->configKey)
            ->update(['value' => self::OFF]);
        if (!$process) {
            $return = ['error' => 1, 'msg' => gp247_language_render('admin.extension.action_error', ['action' => 'Disable'])];
        }

        //Admin config home
        AdminHome::where('extension', $this->appPath)->update(['status' => 0]);

        return $return;
    }

    /**
     * Get info template
     *
     * @return  [type]  [return description]
     */
    public function getInfo()
    {
        $arrData = [
            'title' => $this->title,
            'key' => $this->configKey,
            'image' => $this->image,
            'permission' => self::ALLOW,
            'version' => $this->version,
            'auth' => $this->auth,
            'link' => $this->link,
            'appPath' => $this->appPath
        ];

        return $arrData;
    }

    // Remove setup for store


    // Remove setup for store

    public function removeStore($storeId = null)
    {
        if ($storeId) {
            FrontLayoutBlock::where('template', $this->configKey)
                ->where('store_id', $storeId)
                ->delete();
            // WHY: 1-1 ownership — filter banners by their own store_id column.
            $tableBanner = (new FrontBanner)->getTable();
            $idBanners = (new FrontBanner)
                ->where($tableBanner.'.name', 'like', '%('.$this->configKey.')%')
                ->where($tableBanner.'.store_id', $storeId)
                ->pluck('id');

            if ($idBanners) {
                FrontBanner::whereIn('id', $idBanners)
                ->delete();
            }
        } else {
            // Remove from all stories
            FrontLayoutBlock::where('template', $this->configKey)
                ->delete();
            $idBanners = FrontBanner::where('name', 'like', '%('.$this->configKey.')%')
                ->pluck('id');
            if ($idBanners) {
                FrontBanner::whereIn('id', $idBanners)
                ->delete();
            }
        }
    }

    // Setup for store

    public function setupStore($storeId = null)
    {
        if ($storeId) {
        
            // Change template for store
            AdminStore::where('id', $storeId)
                ->update(['template' => $this->configKey]);

            // Insert layout block for store
            $dataInsert[] = [
                'id'       => $this->uuid(),
                'name'     => 'Banner top ('.$this->configKey.')',
                'position' => 'top',
                'page'     => 'front_home',
                'text'     => 'banner_image',
                'type'     => 'view',
                'sort'     => 10,
                'status'   => 1,
                'template' => $this->configKey,
                'store_id' => $storeId,
            ];

            $dataInsert[] = [
                'id'       => $this->uuid(),
                'name'     => 'Welcome ('.$this->configKey.')',
                'position' => 'bottom',
                'page'     => 'front_home',
                'text'     => 'welcome',
                'type'     => 'view',
                'sort'     => 25,
                'status'   => 1,
                'template' => $this->configKey,
                'store_id' => $storeId,
            ];

            $dataInsert[] = [
                'id'       => $this->uuid(),
                'name'     => 'Email subscribe ('.$this->configKey.')',
                'position' => 'bottom',
                'page'     => 'front_home',
                'text'     => 'email_subscribe',
                'type'     => 'view',
                'sort'     => 1,
                'status'   => 1,
                'template' => $this->configKey,
                'store_id' => $storeId,
            ];

            // gp247/shop is optional — only wire up shop-backed blocks if its models are installed
            if (class_exists(\GP247\Shop\Models\ShopCategory::class)) {
                $dataInsert[] = [
                    'id'       => $this->uuid(),
                    'name'     => 'Category home ('.$this->configKey.')',
                    'position' => 'bottom',
                    'page'     => 'front_home',
                    'text'     => 'shop_category_home',
                    'type'     => 'view',
                    'sort'     => 20,
                    'status'   => 1,
                    'template' => $this->configKey,
                    'store_id' => $storeId,
                ];
            }

            if (class_exists(\GP247\Shop\Models\ShopProduct::class)) {
                $dataInsert[] = [
                    'id'       => $this->uuid(),
                    // Name and key both say what this lists — active price promotions, not
                    // the time-boxed ProductFlashSale plugin. The key is the blade file name
                    // AND what the Layout Block screen offers the site owner, so it could not
                    // stay `shop_flash_sale` next to the plugin's `product_flash_sale`.
                    'name'     => 'Promotion products ('.$this->configKey.')',
                    'position' => 'bottom',
                    'page'     => 'front_home',
                    'text'     => 'shop_product_promotion',
                    'type'     => 'view',
                    'sort'     => 15,
                    'status'   => 1,
                    'template' => $this->configKey,
                    'store_id' => $storeId,
                ];

                $dataInsert[] = [
                    'id'       => $this->uuid(),
                    'name'     => 'Product home ('.$this->configKey.')',
                    'position' => 'bottom',
                    'page'     => 'front_home',
                    'text'     => 'shop_product_home',
                    'type'     => 'view',
                    'sort'     => 5,
                    'status'   => 1,
                    'template' => $this->configKey,
                    'store_id' => $storeId,
                ];

                foreach ($this->ecExtraHomeBlocks($storeId) as $ecRow) {
                    $dataInsert[] = $ecRow;
                }

                // WHY GP247-EC seeds the shop's "last viewed" block itself: gp247/shop
                // seeds it only for the template active when the shop is installed, and
                // switching template runs the outgoing template's removeStore(), which
                // deletes it. One row covers listing + search (layout_page list).
                $dataInsert[] = $this->ecBlockRow($storeId, 'Product last view', 'left', 'shop_product_list,shop_search', 'shop_product_last_view', 20);
            }

            // Blocks owned by plugins (gp247-config.front.layout_block_views). A plugin
            // places its block for the template active at plugin install; a template
            // switch deletes that placement, so place it again — only for plugins
            // registered right now, by block name, never importing a plugin class.
            $registered = (array) config('gp247-config.front.layout_block_views', []);
            foreach ([
                'product_flash_sale' => ['Flash sale', 'bottom', 'front_home', 18],
                'vendor_new'         => ['Top new vendors', 'bottom', 'front_home', 4],
            ] as $view => [$name, $position, $page, $sort]) {
                if (array_key_exists($view, $registered)) {
                    $dataInsert[] = $this->ecBlockRow($storeId, $name, $position, $page, $view, $sort);
                }
            }

            // WHY: setupStore() can run again for the same store (template re-assigned,
            // or a store created while GP247-EC is active) — never duplicate a block.
            $existing = FrontLayoutBlock::where('store_id', $storeId)
                ->where('template', $this->configKey)
                ->pluck('text')
                ->all();
            $dataInsert = array_values(array_filter($dataInsert, fn ($row) => !in_array($row['text'], $existing, true)));
            if ($dataInsert) {
                FrontLayoutBlock::insert($dataInsert);
            }

            // Sample banners once per store (same re-run guard as the blocks above).
            $hasBanners = FrontBanner::where('store_id', $storeId)
                ->where('name', 'like', '%('.$this->configKey.')%')
                ->exists();
            if ($hasBanners) {
                return null;
            }

            // WHY: 1-1 ownership — each banner carries its own store_id column, so
            // the owning store is set on the banner row itself (no pivot insert).
            $modelBanner = new FrontBanner;

            $modelBanner->create(['id' => $this->uuid(), 'name' => 'Banner home 1 ('.$this->configKey.')', 'image' => 'https://static.gp247.net/file/s-cart/where/large/pic1.jpg', 'target' => '_self', 'html' => '', 'status' => 1, 'type' => 'banner', 'store_id' => $storeId]);
            $modelBanner->create(['id' => $this->uuid(), 'name' => 'Banner home 2 ('.$this->configKey.')', 'image' => 'https://static.gp247.net/file/s-cart/where/large/pic2.jpg', 'target' => '_self', 'html' => '', 'status' => 1, 'type' => 'banner', 'store_id' => $storeId]);
            $modelBanner->create(['id' => $this->uuid(), 'name' => 'Banner breadcrumb ('.$this->configKey.')', 'image' => 'https://static.gp247.net/file/s-cart/where/large/pic3.jpg', 'target' => '_self', 'html' => '', 'status' => 1, 'type' => 'breadcrumb', 'store_id' => $storeId]);
            $modelBanner->create(['id' => $this->uuid(), 'name' => 'Banner store ('.$this->configKey.')', 'image' => 'https://static.gp247.net/file/s-cart/where/large/pic4.jpg', 'target' => '_self', 'html' => '', 'status' => 1, 'type' => 'banner-store', 'store_id' => $storeId]);
        } else {
            return null;
        }
    }

    /**
     * One layout-block row owned by this template for a store.
     *
     * @param string $storeId  Store the row belongs to.
     * @param string $name     Block name (the template key is appended).
     * @param string $position Layout position (top, left, bottom…).
     * @param string $page     Layout page(s), comma separated.
     * @param string $view     Block view name (blocks/<view> or a plugin block).
     * @param int    $sort     Higher shows first within the position.
     * @return array<string, mixed>
     */
    private function ecBlockRow($storeId, string $name, string $position, string $page, string $view, int $sort): array
    {
        return [
            'id'       => $this->uuid(),
            'name'     => $name.' ('.$this->configKey.')',
            'position' => $position,
            'page'     => $page,
            'text'     => $view,
            'type'     => 'view',
            'sort'     => $sort,
            'status'   => 1,
            'template' => $this->configKey,
            'store_id' => $storeId,
        ];
    }

    /**
     * Home-page layout blocks GP247-EC adds on top of the default set:
     * the "top picks by category" tabs and the brand shelf (bottom position,
     * below the newest products, above the newsletter band).
     *
     * @param string $storeId Store the rows belong to.
     * @return array<int, array<string, mixed>> Rows for FrontLayoutBlock::insert().
     */
    private function ecExtraHomeBlocks($storeId): array
    {
        $rows = [];
        foreach ([['Category tabs', 'shop_category_tabs', 3], ['Brands home', 'shop_brand_home', 2]] as [$name, $view, $sort]) {
            $rows[] = [
                'id'       => $this->uuid(),
                'name'     => $name.' ('.$this->configKey.')',
                'position' => 'bottom',
                'page'     => 'front_home',
                'text'     => $view,
                'type'     => 'view',
                'sort'     => $sort,
                'status'   => 1,
                'template' => $this->configKey,
                'store_id' => $storeId,
            ];
        }

        return $rows;
    }

    /**
     * Converge the data of stores already using this template after its files
     * changed: add any GP247-EC home block a store does not have yet.
     * Idempotent and independent of $fromVersion (core may pass null on a site
     * with no recorded version) — it checks the current rows, never the version.
     *
     * @param string|null $fromVersion Version installed before the file replacement.
     * @return array ['error' => 0|1, 'msg' => string]
     *
     */
    public function update(?string $fromVersion = null)
    {
        if (!class_exists(\GP247\Shop\Models\ShopProduct::class)) {
            return ['error' => 0, 'msg' => ''];
        }
        try {
            $storeIds = AdminStore::where('template', $this->configKey)->pluck('id');
            foreach ($storeIds as $storeId) {
                foreach ($this->ecExtraHomeBlocks((string) $storeId) as $row) {
                    $exists = FrontLayoutBlock::where('template', $this->configKey)
                        ->where('store_id', $row['store_id'])
                        ->where('text', $row['text'])
                        ->where('position', $row['position'])
                        ->exists();
                    if (!$exists) {
                        FrontLayoutBlock::insert($row);
                    }
                }
            }
        } catch (\Throwable $e) {
            return ['error' => 1, 'msg' => $e->getMessage()];
        }

        return ['error' => 0, 'msg' => ''];
    }

    private function uuid() {
        // While install template from command, cannot load helper gp247
        if(app()->runningInConsole()) {
            return (string)\Illuminate\Support\Str::orderedUuid();
        } else {
            return gp247_uuid();
        }
    }
}

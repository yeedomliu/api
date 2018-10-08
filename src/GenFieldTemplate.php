<?php

@ob_start();
echo "<?php\n";
?>

namespace <?=$namespace?>;

trait <?=$ucfirstName.PHP_EOL?>
{

    /**
     * <?=$desc.PHP_EOL?>
     *
     * @var string
     */
    protected $<?=$name?> = '';

    /**
     * @return string
     */
    public function get<?=$ucfirstName?>(): string {
        return $this-><?=$name?>;
    }

    /**
     * @param string $<?=$name.PHP_EOL?>
     *
     * @return $this
     */
    public function set<?=$ucfirstName?>(string $<?=$name?>) {
        $this-><?=$name?> = $<?=$name?>;

        return $this;
    }


}
<?php return ob_get_contents(); ?>
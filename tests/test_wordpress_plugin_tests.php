<?php

/**
 * Tests to test that that testing framework is testing tests. Meta, huh?
 *
 * @package wordpress-plugins-tests
 */
class WP_Test_WordPress_Plugin_Tests extends WP_UnitTestCase
{

    /**
     * Run a simple test to ensure that the tests are running
     */
    function test_setAsFeaturedCreatesPostMeta()
    {
        $p = $this->factory->post->create();

        $_POST['insert_featured_post'] = 'yes';
        YIW_add_featured($p);
        $this->assertEquals(1, get_post_meta($p, '_yiw_featured_post', true));

	 }

    function test_unsetAsFeaturedDeletePostMeta()
    {
        $p = $this->factory->post->create();

        $_POST['insert_featured_post'] = 'yes';
        YIW_add_featured($p);
        $this->assertEquals(1, get_post_meta($p, '_yiw_featured_post', true));
        $_POST['insert_featured_post'] = 'no';
        YIW_add_featured($p);
        $this->assertEquals(array(), get_post_meta($p, '_yiw_featured_post'));
    }

    function test_invalidValueForPOSTDoesNotSetFeaturedPost() {
        $p = $this->factory->post->create();
        $_POST['insert_featured_post'] = 'strange-value';
        $this->assertEquals(array(), get_post_meta($p, '_yiw_featured_post'));
    }

}

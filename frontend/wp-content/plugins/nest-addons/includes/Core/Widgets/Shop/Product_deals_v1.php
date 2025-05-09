<?php
namespace  Nestaddons\Core\Widgets\Shop;
if (!defined('ABSPATH')) {
    exit;
} // If this file is called directly, abort.
class Product_deals_v1 extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'nest-product-deals-v1';
    }
    public function get_title()
    {
        return __('Product Deals V1', 'nest-addons');
    }
    public function get_icon()
    {
        return 'icon-letter-n';
    }
    public function get_categories()
    {
        return ['103'];
    }
    protected function register_controls(){
        // style one start
        $this->start_controls_section('product_deals_v1_settings',
        [ 
            'label' => __('Product Content', 'nest-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]
        );
        $this->add_control(
            'product_style',
            [
                'label' => __('Product style', 'nest-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'style_one'   => esc_html__( 'Style One', 'nest-addons' ),
                    'style_two'   => esc_html__( 'Style Two', 'nest-addons' ), 
                ],
                'default' => 'style_one',
            ]
        );
        $this->add_control(
            'column',
            [
                'label' => __('Deals Column', 'nest-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'col-xl-2 col-lg-6 col-md-6 col-sm-6'   => esc_html__( 'Six Column', 'nest-addons' ),
                    'col-lg-1-5  col-md-4 col-sm-6'   => esc_html__( 'Five Column', 'nest-addons' ),
                    'col-xl-3 col-lg-4 col-md-6 col-sm-6'   => esc_html__( 'Four Column', 'nest-addons' ),
                    'col-xl-4 col-lg-4 col-md-6 col-sm-6'   => esc_html__( 'Three Column', 'nest-addons' ),
                    'col-xl-6 col-lg-6 col-md-6 col-sm-6'   => esc_html__( 'Two Column', 'nest-addons' ),
                    'col-xl-12'   => esc_html__( 'One Column', 'nest-addons' ),
                ],
                'default' => 'col-xl-3 col-lg-4 col-md-6 col-sm-6',
            ]
        );
        $this->add_control(
            'post_count',
            [
                'label' => __('Post Count', 'nest-addons'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 10,
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
            ]
        );
        $this->add_control(
			'query_orderby',
			[
				'label'   => esc_html__( 'Order By', 'nest-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
                    ''  => esc_html__( 'Default', 'nest-addons' ),
					'date'       => esc_html__( 'Date', 'nest-addons' ),
					'title'      => esc_html__( 'Title', 'nest-addons' ),
					'menu_order' => esc_html__( 'Menu Order', 'nest-addons' ),
					'rand'       => esc_html__( 'Random', 'nest-addons' ),
				),
			]
		);
		$this->add_control(
			'query_order',
			[
				'label'   => esc_html__( 'Order', 'nest-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => array(
					'DESC' => esc_html__( 'DESC', 'nest-addons' ),
					'ASC'  => esc_html__( 'ASC', 'nest-addons' ),
				),
			]
        );
        $this->add_control(
            'query_category', 
				[
                    'type' => \Elementor\Controls_Manager::SELECT2, // Use SELECT2 for multiple select
                    'label' => esc_html__('Category', 'nest-addons'),
                    'options' => nest_get_product_categories(),
                    'multiple' => true, // Enable multiple select
			]
        );
        $this->add_control(
            'product_options_showing',
            [
                'label'   => esc_html__( 'Products', 'nest-addons' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    ''   => esc_html__( 'Select Options', 'nest-addons' ),
                    'featured'     => esc_html__( 'Featured', 'nest-addons' ),
                    'best_selling' => esc_html__( 'Best Selling', 'nest-addons' ),
                    'sale'         => esc_html__( 'On Sale', 'nest-addons' ),
                    'outofstock'   => esc_html__( 'In Stock', 'nest-addons' ),
                    'outforsticknew'   => esc_html__( 'Out Of Stock', 'nest-addons' ),
                    'price_low_high' => esc_html__( 'Price Low to High', 'nest-addons' ),
                    'price_high_low' => esc_html__( 'Price High to Low', 'nest-addons' ),
                ],
                'default' => '',
                'toggle'  => false,
            ]
        );
        $this->add_control(
            'product_not_in',
            [
                'label'       => esc_html__( 'Product Not In', 'nest-addons' ),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'default' =>  esc_html__( '' , 'nest-addons'),
            ]
        );
        $this->add_control(
            'deals_disable',
           [
              'label' => __('Enable / Disable Deals Timing', 'nest-addons'),
               'type' => \Elementor\Controls_Manager::SWITCHER,
               'label_on' => __('Yes', 'nest-addons'),
               'label_off' => __('No', 'nest-addons'),
               'return_value' => 'yes',
               'default' => 'no',
           ]
        );
        $this->add_control(
            'deals_second_remining',
           [
              'label' => __('Enable / Disable Seconds Remaining Disable', 'nest-addons'),
               'type' => \Elementor\Controls_Manager::SWITCHER,
               'label_on' => __('Yes', 'nest-addons'),
               'label_off' => __('No', 'nest-addons'),
               'return_value' => 'yes',
               'default' => 'no',
           ]
        );
        $this->add_control(
            'image_height',
            [
                'label' => __('Image Height', 'nest-addons'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 335,
				'min'     => 1,
				'max'     => 1000,
				'step'    => 1,
                'selectors' => [
                    '{{WRAPPER}} .product_deals .deals_style_one .product-img-action-wrap .product-img img ' => 'height: {{VALUE}}px!important;',
                ],
            ]
        );
        $this->add_control(
            'rating_enable',
            [
                'label' => __('Rating Enable / Disable', 'nest-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'nest-addons'),
                'label_off' => __('No', 'nest-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->add_control(
            'product_shold_percentge',
            [
                'label' => __('Product Sold - Available Enable / Disable', 'nest-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'nest-addons'),
                'label_off' => __('No', 'nest-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->add_control(
            'short_description_enable',
            [
                'label' => __('Product Short Description Enable', 'nest-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'nest-addons'),
                'label_off' => __('No', 'nest-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->add_control(
            'transition_enable',
            [
                'label' => __('Transition Enable', 'nest-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'nest-addons'),
                'label_off' => __('No', 'nest-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'wow_animation',
            [
                'label' => esc_html__( 'Transition Timing', 'nest-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '0',
                'options' => [
                    '0'  => esc_html__( '0', 'nest-addons' ),
                    '.1s' => esc_html__( '.1s', 'nest-addons' ),
                    '.2s' => esc_html__( '.2s', 'nest-addons' ),
                    '.3s' => esc_html__( '.3s', 'nest-addons' ),
                    '.4s' => esc_html__( '.4s', 'nest-addons' ),
                    '.5s' => esc_html__( '.5s', 'nest-addons' ),
                    '.6s' => esc_html__( '.6s', 'nest-addons' ),
                    '.7s' => esc_html__( '.7s', 'nest-addons' ),
                    '.8s' => esc_html__( '.8s', 'nest-addons' ),
                    '.9s' => esc_html__( '.9s', 'nest-addons' ),
                    '1s' => esc_html__( '1s', 'nest-addons' ),
                    '1.1s' => esc_html__( '1.1s', 'nest-addons' ),
                    '1.2s' => esc_html__( '1.2s', 'nest-addons' ),
                    '1.3s' => esc_html__( '1.3s', 'nest-addons' ),
                    '1.4s' => esc_html__( '1.4s', 'nest-addons' ),
                    '1.5s' => esc_html__( '1.5s', 'nest-addons' ),
                    '1.6s' => esc_html__( '1.6s', 'nest-addons' ),
                    '1.7s' => esc_html__( '1.7s', 'nest-addons' ),
                    '1.8s' => esc_html__( '1.8s', 'nest-addons' ),
                    '1.9s' => esc_html__( '1.9s', 'nest-addons' ),
                    '2s' => esc_html__( '2s', 'nest-addons' ),
                ],
                'condition' => [
                    'transition_enable' => 'yes'
                ], 
            ]
        );  
    $this->end_controls_section();
    $this->start_controls_section('product_deal_css',
    [ 
        'label' => __('Product Deal Css', 'nest-addons'),
        'tab' =>\Elementor\Controls_Manager::TAB_STYLE,
    ]
    );
    $this->add_control(
        'deal_bg_color',
         [
            'label' => __('Deal Bg Color', 'nest-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .countdown-section ' => 'background: {{VALUE}}!important;',
            ],
         ]
    );
    $this->add_control(
        'deal_content_color',
         [
            'label' => __('Deal Number Color', 'nest-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .countdown-section .countdown-amount' => 'color: {{VALUE}}!important;',
            ],
         ]
    );
    $this->add_control(
        'deal_content_two_color',
         [
            'label' => __('Deal Text Color', 'nest-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .countdown-section .countdown-period  ' => 'color: {{VALUE}}!important;',
            ],
         ]
    );
    $this->add_control(
        'product_title_color',
         [
            'label' => __('Product Title Color', 'nest-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}  .product-content-wrap h2 a  ' => 'color: {{VALUE}}!important;',
            ],
         ]
    );
    $this->add_control(
        'product_rating_color',
         [
            'label' => __('Product Rating Color', 'nest-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}  .star-rating::before  ' => 'color: {{VALUE}}!important;',
            ],
         ]
    );
    $this->add_control(
        'product_rating_active_color',
         [
            'label' => __('Product Rating Active Color', 'nest-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}  .star-rating span::before  ' => 'color: {{VALUE}}!important;',
            ],
         ]
    );
    $this->add_control(
        'product_rating_count_color',
         [
            'label' => __('Product Rating Count Color', 'nest-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}  .product-rate-cover .font-small ' => 'color: {{VALUE}}!important;',
            ],
         ]
    );
    $this->add_control(
        'product_vendor_color',
         [
            'label' => __('Product Vendor Text Color', 'nest-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}  .product-content-wrap .font-small.text-muted ' => 'color: {{VALUE}}!important;',
            ],
         ]
    );
    $this->add_control(
        'product_vendor_two_color',
         [
            'label' => __('Product Vendor Color', 'nest-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}  .product-content-wrap .font-small.text-muted a ' => 'color: {{VALUE}}!important;',
            ],
         ]
    );
    $this->add_control(
        'product_price_color',
         [
            'label' => __('Product Price Offer Color', 'nest-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}  .product-content-wrap .product-price span ' => 'color: {{VALUE}}!important;',
            ],
         ]
    );
    $this->add_control(
        'product_price_og_color',
         [
            'label' => __('Product Price Color', 'nest-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .product-price del , {{WRAPPER}}    .product-price del bdi , {{WRAPPER}}    .product-price del bdi  .woocommerce-Price-currencySymbol ' => 'color: {{VALUE}}!important;',
            ],
         ]
    );
    $this->add_control(
        'button_colors',
         [
            'label' => __('Product Btn Color', 'nest-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}  .product-card-bottom .add-cart .add , {{WRAPPER}}  .product-card-bottom .add-cart .add span , {{WRAPPER}}  .product-card-bottom .add-cart  a::before , {{WRAPPER}} .product_deal_three .add-cart a  ' => 'color: {{VALUE}}!important;',
            ],
         ]
    );
    $this->add_control(
        'button_bg_color',
         [
            'label' => __('Product Btn Bg Color', 'nest-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}  .product-card-bottom .add-cart .add , {{WRAPPER}}  .product-card-bottom .add-cart .add span ,
				{{WRAPPER}} .product_deal_three .add-cart a' => 'background: {{VALUE}}!important;',
            ],
         ]
    );
    $this->add_control(
        'box_color',
         [
            'label' => __('Box Bg Color', 'nest-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .style-2 .product-content-wrap .deals-content ' => 'background: {{VALUE}}!important;',
            ],
         ]
    );
    $this->end_controls_section();
    }
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $allowed_tags = wp_kses_allowed_html('post');
    ?>
<section
    class="product_deals position-relative <?php if(!empty($settings['deals_second_remining'] == 'yes')): ?> seconds_enable <?php endif; ?>">
    <div class="row">
        <?php  $product_not_inside = '';
                if(!empty($settings['product_not_in'])){
                    $product_not_inside = explode(',', $settings['product_not_in']);
                }
                else{
                    $product_not_inside = '0';
                }
                $query_args = array(
                        'post_type' => 'product',
                        'ignore_sticky_posts' => true,
                        'posts_per_page' => $settings['post_count'],
                        'orderby'        => $settings['query_orderby'],
                        'order'          =>  $settings['query_order'],
                        'post__not_in'   => $product_not_inside ,
                    );
                    if (!empty($settings['query_category']) && is_array($settings['query_category'])) {
                        $category_string = implode(',', $settings['query_category']);
                        $query_args['product_cat'] = $category_string;
                    }
                    if($settings['product_options_showing'] == 'outofstock'):
                        $query_args['meta_key'] = '_stock_status';
                        $query_args['meta_compare'] = '=';
                        $query_args['meta_value'] = 'outofstock';
                    elseif($settings['product_options_showing'] == 'outforsticknew'):
                        $query_args['meta_key'] = '_stock_status';
                        $query_args['meta_compare'] = '=';
                        $query_args['meta_value'] = 'instock';
                        elseif($settings['product_options_showing'] == 'best_selling'):
                            $query_args['meta_key'] = 'total_sales';
                            $query_args['orderby'] = 'meta_value_num';
                            $query_args['order']    = 'DESC';
                        elseif($settings['product_options_showing'] == 'featured'):
                            $query_args['tax_query'] = array( array(
                                'taxonomy' => 'product_visibility',
                                'field'    => 'name',
                                'terms'    => array( 'featured' ),
                                'operator' => 'IN',
                            ) 
                        );
                        elseif($settings['product_options_showing'] == 'sale'):
                            $query_args['meta_key'] = '_sale_price';
                            $query_args['meta_value'] = array('');
                            $query_args['meta_compare'] = 'NOT IN';
                        elseif ($settings['product_options_showing'] == 'price_low_high'):
                            $query_args['meta_key'] = '_price';
                            $query_args['orderby'] = 'meta_value_num';
                            $query_args['order'] = 'ASC';
                        elseif ($settings['product_options_showing'] == 'price_high_low') :
                            $query_args['meta_key'] = '_price';
                            $query_args['orderby'] = 'meta_value_num';
                            $query_args['order'] = 'DESC';
                        endif;
                        $product_query = new \WP_Query( $query_args );
                    ?>
        <?php if($product_query->have_posts()):
                while($product_query->have_posts()) : $product_query->the_post();
                global $product , $post , $woocommerce;
                $product_deals_images =   get_post_meta(get_the_ID() , 'product_deals_image', true);        
                $porduct_store_name = get_post_meta(get_the_ID() , 'porduct_store_name', true);
                $porduct_store_link = get_post_meta(get_the_ID() , 'porduct_store_link', true);
                // while loop start ?>
        <div class="<?php echo esc_attr($settings['column']); ?>">
            <div <?php wc_product_class( '', $product ); ?>>
            <?php if($settings['product_style'] == 'style_two'): ?>
                <div class="product_deal_three d-flex align-items-center <?php if($settings['transition_enable'] == 'yes'): ?> wow animate__animated animate__fadeInUp"
                    data-wow-delay="<?php echo esc_attr($settings['wow_animation']); ?><?php endif; ?>">
                    <div class="product-badges product-badges-position product-badges-mrg">
                    <?php  do_action('get_nest_sales_badges'); ?>
                    </div>
                    <div class="product-img-action-wrap">
                        <div class="product-img product-img-zoom">
                            <a href="<?php echo esc_url(get_permalink(get_the_id())); ?>">
                                <?php echo woocommerce_get_product_thumbnail('default-img'); ?>
                                <?php do_action('get_nest_hover_product_image'); ?>
                            </a>
                        </div> 
                    </div>
                    <div class="product-content-wrap"> 
                    <div class="deals-content">  
                        <?php if($settings['rating_enable'] == 'yes'): nest_get_star_rating();  endif; ?>
                            <h2 class="pro_title"><a
                                    href="<?php echo esc_url(get_permalink(get_the_id())); ?>"><?php the_title(); ?></a>
                            </h2>
                            <?php if($settings['product_shold_percentge'] == 'yes'): do_action('get_nest_shop_product_sold_count_default');  endif; ?> 
                            <?php if($settings['short_description_enable'] == 'yes'): 
                                $short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );
                                if(!empty($short_description)):
                                ?>
                            <div class="woocommerce-product-details__short-description">
                                <?php echo $short_description; // WPCS: XSS ok. ?>
                            </div>
                            <?php endif; endif;  ?> 
                            <div class="product-card-bottom">
                                <div class="product-price clearfix">
                                    <?php woocommerce_template_loop_price(); ?>
                                </div>
                                <div class="d-flex bottom_car align-items-center">
                                <div class="add-cart">
                                    <?php woocommerce_template_loop_add_to_cart(); ?>
                                </div>
                                <?php if($settings['deals_disable'] == 'yes'): ?> 
                                <div class="deals-countdown-wrap">
                                    <?php do_action('get_nest_product_deals');?>
                                </div>
                                <?php endif; ?> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                    <div class="product_wrapper product-cart-wrap deals_style_one style-2<?php if($settings['transition_enable'] == 'yes'): ?> wow animate__animated animate__fadeInUp"
                    data-wow-delay="<?php echo esc_attr($settings['wow_animation']); ?><?php endif; ?>">
                    <div class="product-img-action-wrap">
                        <div class="product-img">
                            <?php if(!empty($product_deals_images['url'])): ?>
                            <?php if(is_array($product_deals_images) || is_object($product_deals_images)): ?>
                            <img src="<?php echo esc_url($product_deals_images['url']); ?>" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                            <?php else: ?>
                            <a href="<?php echo esc_url(get_permalink(get_the_id())); ?>">
                                <?php echo woocommerce_get_product_thumbnail('default-img'); ?>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="product-content-wrap">
                    <?php if($settings['deals_disable'] == 'yes'): ?> 
                                <div class="deals-countdown-wrap">
                                    <?php do_action('get_nest_product_deals');?>
                                </div>
                                <?php endif; ?> 
                        <div class="deals-content"> 
                        <?php if($settings['rating_enable'] == 'yes'): nest_get_star_rating();  endif; ?>
                            <h2 class="pro_title"><a
                                    href="<?php echo esc_url(get_permalink(get_the_id())); ?>"><?php the_title(); ?></a>
                            </h2>
                            <?php if($settings['product_shold_percentge'] == 'yes'): do_action('get_nest_shop_product_sold_count_default');  endif; ?>
                            <?php if($settings['short_description_enable'] == 'yes'): 
                                $short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );
                                if(!empty($short_description)):
                                ?>
                            <div class="woocommerce-product-details__short-description">
                                <?php echo $short_description; // WPCS: XSS ok. ?>
                            </div>
                            <?php endif; endif;  ?>
                            <?php if(!empty($porduct_store_name)): ?>
                            <div>
                                <span class="font-small text-muted"><?php echo esc_html('By' , 'nest-addons') ?>
                                    <a href="<?php if(!empty($porduct_store_link)): ?><?php echo esc_attr($porduct_store_link); else: echo esc_html('#','nest-addons'); endif;?>"
                                        target="_blank">
                                        <?php echo esc_attr($porduct_store_name); ?>
                                    </a>
                                </span>
                            </div>
                            <?php endif; ?>
                            <div class="product-card-bottom">
                                <div class="product-price clearfix">
                                    <?php woocommerce_template_loop_price(); ?>
                                </div>
                                <div class="add-cart">
                                    <?php woocommerce_template_loop_add_to_cart(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            </div>
        </div>
        <?php endwhile; // while loop end ?>
        <?php wp_reset_postdata(); ?>
        <?php endif; // Post Endif after loop end  ?>
    </div>
    <!--End tab-content-->
</section>
<?php
    }
}
 
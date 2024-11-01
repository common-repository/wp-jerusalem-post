<?php
class jPostWidget extends WP_Widget
{
	
	function jPostWidget()
	{
		$widget_options = array(
			'classname'		=>	'jerusalempost-widget',
			'description'	=>	'A widget to show JPost.com\'s feed'
		);

		parent::WP_Widget(false, 'Jerusalem Post Widget', $widget_options);
	}
	
	function widget($args, $instance)
	{
		extract($args, EXTR_SKIP );
		$jpost_feed = ( $instance['jpost_feed'] ) ? $instance['jpost_feed'] : 'No Jerusalem Post Feed';
		$jpost_number = ( $instance['jpost_number'] ) ? $instance['jpost_number'] : '3';

        echo $before_widget;
		echo $before_title . $instance['title'] . $after_title;

		if ($jpost_feed != 'No Jerusalem Post Feed')
		{
			$feed_array = jerusalempost_scrubber(
				$jpost_feed,
				40
				);

			jerusalempost_echo(
				$feed_array,
				$jpost_number, 
				$this->number //$this->number is the Widgets number that WP generate
				);
		}
        echo $after_widget;
	}

    function update($new_instance, $old_instance) {
        $instance = array();

        $instance['title'] = strip_tags(trim($new_instance['title']));
        $instance['jpost_feed'] = strip_tags($new_instance['jpost_feed']);
        $instance['jpost_number'] = absint(strip_tags($new_instance['jpost_number']));

        if($instance['jpost_number'] > 38)
            $instance['jpost_number'] = 38;

        return $instance;
    }

	function form($instance)
	{
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'title' => 'Jerusalem Post',
                'jpost_number' => 3,
                'jpost_feed' => 'http://www.jpost.com/RSS/RssFeedsFrontPage.aspx',
            )
        );
		$feeds = array (
			'Front Page' => 'http://www.jpost.com/RSS/RssFeedsFrontPage.aspx',
			'Headlines' => 'http://www.jpost.com/RSS/RssFeedsHeadlines.aspx',
			'Diplomacy & Politics' => 'http://www.jpost.com/Rss/RssFeedsDiplomacyAndPolitics.aspx',
			'Defense' => 'http://www.jpost.com/Rss/RssFeedsDefense.aspx',
			'National News' => 'http://www.jpost.com/Rss/RssFeedsIsraelNews.aspx',
			'Middle East News' => 'http://www.jpost.com/Rss/RssFeedsMiddleEastNews.aspx',
			'International News' => 'http://www.jpost.com/Rss/RssFeedsInternationalNews.aspx',
			'Iranian Threat' => 'http://www.jpost.com/Rss/RssFeedsIT.aspx',
			'Business' => 'http://www.jpost.com/Rss/RssFeedsBusiness.aspx',
			'Real Estate' => 'http://www.jpost.com/Rss/RssFeedsRealEstate.aspx',
			'Sports' => 'http://www.jpost.com/Rss/RssFeedsSports.aspx',
			'Sci-Tech' => 'http://www.jpost.com/Rss/RssFeedsSciTech.aspx',
			'Opinion' => 'http://www.jpost.com/Rss/RssFeedsOpinion.aspx',
			'Editorials' => 'http://www.jpost.com/Rss/RssFeedsEditorialsNews.aspx',
			'Op-Ed Contributors' => 'http://www.jpost.com/Rss/RssFeedsJPostOpEdContributors.aspx',
			'Letters' => 'http://www.jpost.com/Rss/RssFeedsOpinionLetters.aspx',
			'Jewish World' => 'http://www.jpost.com/Rss/RssFeedsJewishWorld.aspx',
			'Jewish World Features' => 'http://www.jpost.com/Rss/RssFeedsJewishWorldFeatures.aspx',
			'Jewish World Judaism' => 'http://www.jpost.com/Rss/RssFeedsJewishWorldJudaism.aspx',
			'Lifestyle' => 'http://www.jpost.com/Rss/RssFeedsLifestyle.aspx',
			'Arts & Culture' => 'http://www.jpost.com/Rss/RssFeedsArtsAndCulture.aspx',
			'Food & Wine' => 'http://www.jpost.com/Rss/RssFeedsFoodAndWine.aspx',
			'Health' => 'http://www.jpost.com/Rss/RssFeedsTravel.aspx',
			'Features' => 'http://www.jpost.com/Rss/RssFeedsFeatures.aspx',
			'Blogs' => 'http://blogs.jpost.com/rss.xml',
			'Blogs - In the News' => 'http://blogs.jpost.com/taxonomy/term/1/all/feed',
			'Blogs - Judaism' => 'http://blogs.jpost.com/taxonomy/term/2/all/feed',
			'Blogs - From the Middle East' => 'http://blogs.jpost.com/taxonomy/term/3/all/feed',
			'Blogs - Lifestyle' => 'http://blogs.jpost.com/taxonomy/term/4/all/feed',
			'Blogs - Aliyah' => 'http://blogs.jpost.com/taxonomy/term/5/all/feed',
			'Blogs - Science & Technology' => 'http://blogs.jpost.com/taxonomy/term/6/all/feed',
			'Video' => 'http://www.jpost.com/Rss/RssFeedsVideo.aspx',
			'In Jerusalem' => 'http://www.jpost.com/Rss/RssFeedsInJerusalem.aspx'
			);
        ?>
	    <label for="<?php echo $this->get_field_id('title');?>">
			Title:<br />
			<input
				id="<?php echo $this->get_field_id('title');?>"
				name="<?php echo $this->get_field_name('title');?>"
				value="<?php echo esc_attr($instance['title']) ?>"
			/><br />
		</label>

		<label for="<?php echo $this->get_field_id('jpost_feed');?>">RSS URL:</label><br />
        <select id="<?php echo $this->get_field_id('jpost_feed');?>"
                name="<?php echo $this->get_field_name('jpost_feed');?>">
            <?php
                foreach($feeds as $name=>$feed) {
                    echo '<option value="'.$feed.'"';
                    echo ($instance['jpost_feed'] == $feed)?' selected="selected"':'';
                    echo '>'.$name.'</option>';
                }
            ?>
        </select>

		<label for="<?php echo $this->get_field_id('jpost_number');?>">
			Number to display:<br />
			<input
				id="<?php echo $this->get_field_id('jpost_number');?>"
				name="<?php echo $this->get_field_name('jpost_number');?>"
				value="<?php echo esc_attr($instance['jpost_number']) ?>"
				size="2" maxlength="2"
			/><br />
		</label>
    <?php
	}
}

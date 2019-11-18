<section class="success-story-section">
    <h2 class="success-story-section-heading">Success Story</h2>

    <template id="templateSuccessStorySlider">
        {{#each success_stories}}
        <div>
            <div class="success-story-slider-item" style="background-image: url({{imageBackground}})">
                <blockquote class="success-story">
                    <p class="success-story-desc">{{ longDesc }}</p>
                    <cite class="success-story-cite">{{ name }}</cite>
                </blockquote>
            </div>
        </div>
        {{/each}}
    </template>
    <div class="success-story-slider default-slider-style" data-content="dev/success-story.json"></div>

    <template id="successStoryPeek">
        <div class="success-story-peek">
            <div class="success-story-peek-img" style="background-image: url({{ imageThumb }});"></div>
            <div class="success-story-peek-desc">
                <div><b>{{ name }}</b></div>
                <small>{{ shortDesc }}</small>
            </div>
        </div>
    </template>
    <button class="success-story-nav success-story-nav--prev"></button>
    <button class="success-story-nav success-story-nav--next"></button>
    <div class="text-center">
        <a class="btn btn--tosca success-story-section-more-btn" href="success-stories.php">More Success Stories</a>
    </div>
</section>

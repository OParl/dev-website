<?php

return [
  /*
   * The locale used for all language dependent input fixes.
   * Note that this can be overridden every single time
   * simply with:
   *
   * ```php
   *   $letterpress->press($input, ['letterpress.locale' => 'en_US'])
   * ```
   *
   * Important: The provided locale must be an IETF language code.
   **/
  'locale'   => 'de_DE',

  'markdown' => [
    'enabled' => false,

    // enable markdown linebreaks (<br />) with 2 spaces at line end
    'enableLineBreaks' => true,

    // parse inside markup blocks?
    'enableParserInMarkup' => true,

    // use "markdown extra" syntax instead of parsedown's defaults
    // this only works if parsedown-extra was installed
    'useMarkdownExtra' => false,
  ],

  // apply markup *fixes*. please note that disabling this also disables
  // the media markup replacements.
  'markup' => [
    'enabled' => true,

    // parsedown does a terrible job at block quotes. let's remedy that
    'blockQuoteFix' => true,

    // adjust the maximum headline level (higher level headlines will be altered
    // to the required level)
    'maxHeadlineLevel' => 1,

    // wrap the given content in a div with the appropriate language attribute
    // this is not recommended in favor of setting a base language on the <html> tag
    'addLanguageInfo' => false,
  ],

  // apply markup and meta information using oEmbed and custom data fetchers
  // TODO: this should support some kind of caching
  'media' => [
    'enabled' => false,

    // when this is enabled, iframes from embedded media will be wrapped
    // in a way that allows for responsive resizing while maintaining a 16/9
    // aspect ratio
    'enableResponsiveIFrames' => true,

    // can be one of link, frame, text or image
    // text is title + description + link, image is image + title (with link)
    'videoEmbedMode' => 'frame',

    'services' => ['YouTube', 'Vimeo', 'Flickr'],

    // if silent fail is enabled, the embedder will just return the original input
    // when something goes wrong along the way, otherwise, a LetterpressException
    // will be thrown
    'silentfail' => false,
  ],

  'microtypography' => [
    'enabled'           => true,
    'enableHyphenation' => true,

    'useDefaults' => true,

    // you only need to add your additional fixers here, most of
    // the stuff is handled by the defaults in config/jolitypo.php
    'additionalFixers' => [],
  ],
];

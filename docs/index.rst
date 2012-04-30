Overview
========

Contents:

.. toctree::
   :maxdepth: 2

Installation
------------

Phar file *(coming soon)*
~~~~~~~~~~~~~~~~~~~~~~~~~

Coming soon.

Composer
~~~~~~~~

Add the following entry to your ``composer.json``:

.. code-block:: json

    { "require": { "pminnieur/sprites": "dev-master" }}

Checkout `detailed package information on Packagist`_.

Clone from GitHub
~~~~~~~~~~~~~~~~~

Clone Sprites git repository:

.. code-block:: console

    git clone git://github.com/pminnieur/sprites.git

Download ``composer.phar`` file and install dependencies:

.. code-block:: console

    wget -nc http://getcomposer.org/composer.phar
    php composer.phar install

Run ``sprites`` executable from ``bin`` directory:

.. code-block:: console

    php bin/sprites

Configuration
-------------

A simple configuration of a ``Sprites\ProcessorInterface`` requires a
``Sprites\Configuration`` instance

.. code-block:: php

    <?php

    use Sprites\Configuration;
    use Sprites\Processor\DynamicProcessor;
    use Imagine\Gd\Imagine;
    use Imagine\Image\Color;

    $imagine = new Imagine();

    $config = new Configuration();
    $config->setImagine($imagine);
    $config->setColor(new Color('fff', 100));
    $config->setImage('web/images/icons.png');
    $config->setStylesheet('web/css/icons.css');
    $config->getFinder()->name('*.png')->in('web/images/icons');
    $config->setSelector(".icon.{{filename}}{background-position:{{pointer}}px 0px}\n");

    $processor = new DynamicProcessor();
    $processor->process($config);

    // ...

Configuration Options
~~~~~~~~~~~~~~~~~~~~~
The following sections describe all the configuration options available on a
``Sprites\Configuration`` instance.

Imagine (***REQUIRED***)
^^^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: php

    <?php
    $config->setImagine($imagine);
    $config->getImagine();

The ``Imagine\ImagineInterface`` instance to use.

Options (***RECOMMENDED***)
^^^^^^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: php

    <?php
    $config->setOptions($options);
    $config->getOptions();

An array of options for the ``Imagine\Image\ManipulatorInterface::save()``
method.

.. note::

    Each ``Imagine\ImageInterface`` adapter has its own subset of options.

Finder (***REQUIRED***)
^^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: php

    <?php
    $config->setFinder($finder);
    $config->getFinder();

The ``Symfony\Component\Finder\Finder`` instance used to find files for your
sprites.

.. note::

    To unleash the full power of this component, read the `Finder`_
    documentation.

Image (***REQUIRED***)
^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: php

    <?php
    $config->setImage($path);
    $config->getImage();

The path to the target image sprite.

.. note::

    If the directory does not exist yet, it will automatically be created.

Color (***OPTIONAL***)
^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: php

    <?php
    $config->setColor($color);
    $config->getColor();

The ``Imagine\Image\Color`` instance to use as background color.

Processor (***OPTIONAL***)
^^^^^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: php

    <?php
    $config->setProcessor($processor);
    $config->getProcessor();

The name of the ``Sprites\Processor\ProcessorInterface`` to use. This
configuration value is only needed if you use the ``Sprites\Generator``
and may be guessed automatically, depending if you set a fixed width or not.

.. note::

    Sprites already supports two different kind of processors:

    - **dynamic:** ``Sprites\Processor\DynamicProcessor``
    - **fixed:** ``Sprites\Processor\FixedProcessor``

Width (***OPTIONAL***)
^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: php

    <?php
    $config->setWidth($width);
    $config->getWidth();

A fixed width for each image in the sprite. This configuration value is only
used in the ``Sprites\Processor\FixedProcessor`` and speeds up generating
the image sprite.

.. note::

    The ``Sprites\Processor\FixedProcessor`` could optionally resize
    your images if they exceed the fixed width.

Stylesheet (***REQUIRED***)
^^^^^^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: php

    <?php
    $config->setStylesheet($path);
    $config->getStylesheet();

The path to the target stylesheet.

.. note::

    If the directory does not exist yet, it will automatically be created.

Selector (***OPTIONAL***)
^^^^^^^^^^^^^^^^^^^^^^^^^
.. code-block:: php

    <?php
    $config->setSelector($selector);
    $config->getSelector();

A string parsed for each image, to be used as the CSS in the generated
stylesheet.

.. note::

    The default value of the ``selector`` is
    ``".{{filename}}{background-position:{{pointer}}px 0px}\n"``.

.. note::

    The string is parsed with `Mustache`_ and there are two available parameters:

    - **``pointer``:** horizontal position of current pointer (in ``px``)
    - **``filename``:** an `ASCIIfied`_ version of the filename.

Usage
-----

Processor
~~~~~~~~~

DynamicProcessor
^^^^^^^^^^^^^^^^
The ``DynamicProcessor`` class is used to generate image sprites from images
with a dynamic width and height.

.. code-block:: php

    <?php

    use Sprites\Configuration;
    use Sprites\Processor\DynamicProcessor;

    $config = new Configuration();
    // ... configure your configuration

    $processor = new DynamicProcessor();
    $processor->process($config);


FixedProcessor
^^^^^^^^^^^^^^
The ``FixedProcessor`` class works similar to the ``DynamicProcessor``. There
are two main differences:

- the ``getWidth()`` configuration value is used to determine the absolute width
  of the final image sprite, to spare recalculating the absolute width each
  time a new image is pasted into the sprite.
- you can optionally enable resizing, resulting in images exceeding the fixed
  width to be scaled down with a correct aspect ratio to fit the fixed width.

.. note::

    The height of the image sprite is still calculated dynamically, but ideally
    only once (e.g. if you use `famfamfam`_ icons which usually are dimensioned
    in ``16px x 16px``, the first image sets the sprites' height to ``16px`` and
    then the height must not be adjusted again).

.. code-block:: php

    <?php

    use Sprites\Configuration;
    use Sprites\Processor\FixedProcessor;

    $config = new Configuration();
    $config->setWidth(16); // fixed width of 16px per image
    // ... configure your configuration

    $processor = new FixedProcessor(array('resize' => true));
    $processor->process($config);

Generator
~~~~~~~~~

The ``Sprites\Generator`` class is used for batch processing multiple
``Sprites\Configuration`` instances with their corresponding
``Sprites\Processor\ProcessorInterface`` instances.

.. code-block:: php

    <?php

    use Sprites\Configuration;
    use Sprites\Generator;
    use Sprites\Processor\DynamicProcessor;
    use Sprites\Processor\FixedProcessor;
    // ... add your processor classes

    $generator = new Generator();

    $config = new Configuration();
    // ... configure your configuration
    $generator->addConfiguration($config);
    // ... add your configurations

    $dynamic = new DynamicProcessor();
    $generator->addProcessor($dynamic);
    $fixed = new FixedProcessor();
    $generator->addProcessor($fixed);
    // ... add your processors

    $generator->generate();

Command Line Interface
~~~~~~~~~~~~~~~~~~~~~~
The Sprites Console is a Command Line Interface tool for simplifying the usage
and generation of image sprites without the need of you actually writing a
single line of PHP code.

sprites generate:dynamic
^^^^^^^^^^^^^^^^^^^^^^^^
The ``generate:dynamic`` command generates image sprites and CSS stylesheets
with dynamic dimensions::

    php sprites generate:dynamic --help

sprites generate:fixed
^^^^^^^^^^^^^^^^^^^^^^
The ``generate:fixed`` command generates image sprites and CSS stylesheets with
a fixed width dimension::

    php sprites generate:fixed --help

.. _`Imagine`: https://github.com/avalanche123/Imagine
.. _`Symfony`: http://symfony.com/
.. _`Finder`: http://symfony.com/doc/current/cookbook/tools/finder.html#index-0
.. _`Mustache`: https://github.com/bobthecow/mustache.php
.. _`ASCIIfied`: http://sourcecookbook.com/en/recipes/8/function-to-slugify-strings-in-php
.. _`famfamfam`: http://famfamfam.com/
.. _`detailed package information on Packagist`: http://packagist.org/packages/pminnieur/sprites

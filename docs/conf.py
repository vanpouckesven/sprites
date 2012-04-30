import sys, os
extensions = []
templates_path = ['_templates']
source_suffix = '.rst'
master_doc = 'index'
project = u'Sprites'
copyright = u'2012, Pierre Minnieur'
version = '0.2'
release = '0.2.0'
language = 'php'
exclude_patterns = ['_build']
pygments_style = 'sphinx'
html_theme = 'default'
html_static_path = ['_static']
htmlhelp_basename = 'Spritesdoc'
latex_documents = [
  ('index', 'Sprites.tex', u'Sprites Documentation',
   u'Pierre Minnieur', 'manual'),
]
man_pages = [
    ('index', 'sprites', u'Sprites Documentation',
     [u'Pierre Minnieur'], 1)
]

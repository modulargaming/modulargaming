# Extending core and modules

You should not modify files in core and modules. You should overwrite the files using [Cascading Filesystem](../kohana/files).

For example, to change the layout copy modulargaming/core/templates/layout.mustache to application/templates/layout.mustache and overwrite
this file. You can extend any type of file as long as you use the same directory structure under application/

Classes are transparently extended. MG_Controller_Example extends Controller_Example. You can create a copy of Controller_Example and overwrite functions of the parent class.

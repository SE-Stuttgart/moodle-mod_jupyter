# Jupyter Moodle plugin

This Moodle plugin integrates Jupyter Notebooks to offer a virtual programming environment.

The plugin connects to a JupyterHub server and authenticates the Moodle users on the JupyterHub Server. That way they
can access a Jupyter notebook from within Moodle.

Automated grading of Jupyter Notebooks is provided through [Otter-Grader](https://otter-grader.readthedocs.io/en/latest/).  
A quick introduction for writing assignments in the Otter-Grader format and a demo Notebook can be fond here:  
[AutograderNotebook.md](documentation/AutograderNotebook.md)  
[demo.ipynb](documentation/demo.ipynb)

## Installation

Install this plugin by navigating to `Site administration > Plugins > Install Plugins`, then drag the plugin `moodle-mod_jupyter.zip` in the *Install plugin from ZIP file* zone. Press the *Install plugin from the ZIP file* button.
![Screenshot showing how to install the plugin. Drag the .zip of the plugin in the install plugin zone in the admin settings under install plugin.](https://raw.githubusercontent.com/SE-Stuttgart/moodle-mod_jupyter/main/documentation/how_to_install.png)

## Plugin Settings

On installation of the plugin, you are prompted to enter the following settings.

### JupyterHub URL
This is the base url *(default: http://localhost:8000/jhub)* of the JupyterHub. This url will be used to embed the Jupyter Notebook via iFrame.
### JupyterHub API URL
This is the base url *(default for docker: http://jupyterhub:8000/jhub)* of the JupyterHub REST API. This url will be used to connect and use the jupyterhub api.
### Gradeservice API URL
This is the base url *(default for docker: http://gradeservice:5000)* of the Gradeservice REST API. This url will be used to connect and use the gradeservice api.
### Jupyterhub JWT Secret
This is necessary for authentification and communication with JupyterHub. It must match with the one set in JupyterHub. Please do not use the default one.
### Jupyterhub API Token
This is necessary for authentification and communication with JupyterHub. It must match with the one set in JupyterHub. Please do not use the default one.

## Create Activity

This plugin is an activity plugin and can be created like any activity plugin.

1. Activate `Edit mode`.
2. Click on `Add an Activity or Resource`. Now the activity creation from appears.
![Screenshot showing the "edit mode" toggle on the top right and the "add an activity or resource" in the center.](https://raw.githubusercontent.com/SE-Stuttgart/moodle-mod_jupyter/main/documentation/how_to_create_activity_1.png)
3. Enter a name for the notebook activity you want to create.
4. Drag the .ipynb file for this activity into this field. You can only have one .ipynb file per activity. If you have more files, create multiple activities.
5. Select if you want to use the automatic gradeservice by Otter-Grader.
![Screenshot showing the activity creation form. The three described options are displayed.](https://raw.githubusercontent.com/SE-Stuttgart/moodle-mod_jupyter/main/documentation/how_to_create_activity_2.png)

## Use Activity

When the activity is created, you can navigate to it by clicking on it directly or via the hierarchy. The activity view page opens that includes an embedded jupyter notebook for you to work on.
![Screenshot showing the embedded Notebook.](https://raw.githubusercontent.com/SE-Stuttgart/moodle-mod_jupyter/main/documentation/how_to_result.png)

## License

**Kuenstliche Intelligenz in die Berufliche Bildung Bringen (KIB3)**
**2022 summer semester student project of University of Stuttgart**

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program. If not, see [GNU license](https://www.gnu.org/licenses).

## Additional resources

- [Moodle official development main page](https://docs.moodle.org/dev/Main_Page)
- [Moodle official output api page](https://docs.moodle.org/dev/Output_API)
- [Moodle official javascript page](https://docs.moodle.org/dev/Javascript_Modules)
- [Moodle official development activity modules page](https://docs.moodle.org/dev/Activity_modules)
- [Moodle programming course](https://www.youtube.com/playlist?list=PLgfLVzXXIo5q10qVXDVyD-JZVyZL9pCq0)

## Development Team

- Buchholz, Max
- Günther, Ralph
- Klaß, Robin
- König, Solveigh
- Marinic, Noah
- Schüle, Maximilian
- Stoll, Timo
- Weber, Raphael
- Wohlfart, Phillip
- Zhang, Yichi
- Zoller, Nick

developed this plugin in the context of the Student Project of University of Stuttgart in the Summer Semester 2022

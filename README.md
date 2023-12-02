# Jupyter Moodle plugin

This Moodle plugin integrates Jupyter Notebooks to offer a virtual programming environment.

The plugin connects to a JupyterHub server and authenticates the Moodle users on the JupyterHub Server. That way they
can access a Jupyter notebook from within Moodle.

Automated grading of Jupyter Notebooks is provided through [Otter-Grader](https://otter-grader.readthedocs.io/en/latest/).  
A quick introduction for writing assignments in the Otter-Grader format and a demo Notebook can be fond here:  
[AutograderNotebook.md](documentation/AutograderNotebook.md)  
[demo.ipynb](documentation/demo.ipynb)

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

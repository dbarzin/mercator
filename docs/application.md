# Application

🇫🇷 [Lire en français](/mercator/fr/application)

*📑 Note: You can click on any image to increase its size*

   [<img src="/mercator/images/homepage.png" width="700">](images/homepage.png)

---

### Main page

The main page is divided into three parts:

* Compliance levels

   [<img src="/mercator/images/maturity.png" width="700">](images/maturity.png)

* The distribution of cartography objects by domain. 

   [<img src="/mercator/images/repartition.png" width="700">](images/repartition.png)


* The global proportional map of cartography objects.

   [<img src="/mercator/images/treemap.png" width="700">](images/treemap.png)

Each item is selectable and gives access to the list of selected map objects.

---

### Menus
  [<img src="/mercator/images/menu.png" width="500">](images/menu.png)

* 📕 The left side menu provides access to:
    * the dashboard
    * through the views, to the various cartographic object management screens
    * role and user management screens
    * the logout button
* 📘 The top menu gives access to :
    * ☰ to hide the left side menu
    * [Views](./cartography.md)
    * Preferences
    * Tools (exploration, dependencies, reports...)
    * On line help (Documentation, Guide...)
* 📒 Search tool

---

### Mapping Explorer

It is possible to explore the cartography. This function is available via the top menu in **Tools** tab.


#### Filters
  [<img src="/mercator/images/expfilters.png" width="700">](images/expfilters.png)

- The filter drop-down menu allows you to limit exploration to one or more areas.
- The "Object" field is used to select an element of the cartography and add it to the exploration by clicking on the `add` button.

---

#### Refinement
  [<img src="/mercator/images/expdepth.png" width="700">](images/expdepth.png)

- The "Delete" button is used to remove an element from the cartography exploration.
- The "Restart" button resets the search.
- Depth and direction allow you to fine‑tune the dependency view.
- The "Deploy" button launch the exploration if an objet has been added.

---

#### Saving and rendering
  [<img src="/mercator/images/expsave.png" width="500">](images/expsave.png)

- You can save the generated image by clicking the button located below the visualization.
- You can change the rendering style using the menu located below the visualization.

---
#### Result
  [<img src="/mercator/images/explore.png" width="700">](images/explore.png)

Double-click on an object to display all its connections.

💡 *To get more information, please rely on the [Exploration Feature page](./exploration.md)*

---

### Dependency Analysis

Mercator allows you to analyze the dependency graph of any object in the information system, upstream or downstream, and across multiple levels. This analysis screen is accessible from the top menu **"tools"**. 

#### Filters
  [<img src="/mercator/images/depfilters.png" width="700">](images/depfilters.png)

- The filter dropdowns (Type or Attributes) allow you to narrow the search and display to one or several domains or attributes.
- The **“Object”** field allows you to select an item from the mapping and use it as the starting point for dependency lookup.
- 📢 *Note: the dependency display is restricted by the active filters.*

---

#### Refinement
  [<img src="/mercator/images/depdepth.png" width="700">](images/depdepth.png)

- The **“Restart”** button resets the search.
- Depth and direction allow you to fine‑tune the dependency view.

---

#### Rendering
  [<img src="/mercator/images/depsave.png" width="500">](images/depsave.png)

- You can download the generated image by clicking the button located below the visualization.
- You can change the rendering style using the menu located below the visualization.

---

#### Result
  [<img src="/mercator/images/dependency.png" width="700">](images/dependency.png)
Double‑clicking an object exits the dependencies view and displays all its information.

💡 *To get more information, please rely on the [Dependency Feature page](./dependency.md)*
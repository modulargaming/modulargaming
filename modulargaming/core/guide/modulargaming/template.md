# Templates

[Mustache](https://github.com/mustache) is a template specification we use for our templates, we use 
 [Kostache](https://github.com/zombor/KOstache)'s implementation.


		<ul>
			<li><a href="{{links.index}}">Index</a></li>
			<li><a href="{{links.page}}">Page</a></li>
		</ul>

		<ul>
			{{#module}}

				<li>{{id}}</li>
				<li>{{user_id}}</li>
			{{/module}}

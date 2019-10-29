
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		
		*{
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}
	
body>div{
	overflow: hidden;
	border: 1px solid #000;
	position: relative;
	margin: 1em auto;
	width: 15em;
	height: 15em;
	transition-duration: 1s;
}
body>div>img{
	transition-duration: 1s;
	width: 100%;
	height: 100%;
}
body>div>p{
	position: absolute;
	top: -2.5em;
	left: 0;
	height: 2.5em;
	width: 100%;
	display: flex;
	justify-content: center;
	align-items: center;
	background-color: rgba(0,0,0,0.5);
	transition-duration: 1s;
}
body>div>button{
	position: absolute;
	height: 2.5em;
	width: 10em;
	border: 1px solid #000;
	background-color: rgba(0,0,0,0.5);
	color: #fff;
	top: 60%;
	left: calc(50% - 5em);
	display: none;
	transition-duration: 1s;
}
body>div:hover{
	background-color: rgba(0,0,0,0.2);
}

body>div:hover img{
	transform: scale(1.5);
}

body>div:hover p{
	transform: translateY(5em);
}
body>div:hover button{
	display: block;
}	
	</style>
</head>
<body>
	<div>
		<img src="http://placehold.it/350x200">
		<p>Label</p>
		<button>Button</button>
	</div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		
		span{
			border : 1px solid;
		}

	</style>
</head>
<body>

Hello

	@forelse($products as $product)  

	<div>
		<span><img width="20px" height="20px" src="{{$product->images[0]->src}}"></span><span>{{$product->id}}</span><span>{{$product->title}}</span>
	</div>

	
         
    
    @empty
    	No record Found
    @endforelse
<a href="/authCallback">Register</a>


</body>
</html>
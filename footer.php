  
    <script src="https://code.jquery.com/jquery-3.1.0.js"
integrity="sha256-slogkvB1K3VOkzAI8QITxV3VzpOnkeNVsKvtkYLMjfk="
crossorigin="anonymous"></script>

	<script type="text/javascript">

		$("#diary").on("input selectionchange propertychange",function(){
			// on user input bind an ajax request to send data to mySQL in realtime so text is automatically saved

				$.ajax({
				  method: "POST",
				  url: "updateDataBase.php",
				  data: { content: $("#diary").val() }

				})
				
		});

		$(".toggleForm").click(function(){
			
			$("#signUpForm").toggle();
			$("#logInForm").toggle();
		});

	</script>


</body>
</html>


<script>
    $(document).ready(function(){
      
      let showRacks = function(racks){
        $("#bingos").html('');
        racks.map(rack=>{
          $("#bingos").append(`<li>${rack.rack}: <span class="answer hidden">${rack.words}</span></li>`);
        });
        $("#bingos li").on("click", function(evt){
          $(evt.currentTarget).find(".answer").toggleClass("hidden");
        });
      }
      
      $("#grabmore").on("click", function(){
        $.ajax({
            method: "GET",
            url: "api.php",
            success: data=>{ showRacks(data)}
        });
      });
    });
  </script>
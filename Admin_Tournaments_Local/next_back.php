<script>

  total_players = 128;
//alert("Players from include " + total_players);

    $("#nextBtn").click(function()
    {
      alert("Players " + total_players);
      alert("Content 1 " + $("#content1").is(':visible'));
      alert("Content 2 " + $("#content2").is(':visible'));
      if(total_players == 128)
      {
        if(($("#content1").is(':visible')) && ($("#content2").is(':visible')))
        {
          alert("Content 1 and 2 are visible");
          $("#header128").css({display: "none"});
          $("#header64").css({display: "block"});
          $("#header32").css({display: "block"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          $("#content1").hide();
          $("#content2").show();
          $("#content3").show();
          $("#content4").hide();
          $("#content5").hide();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
      }
      /*
      if(total_players > 32)
      {
        if(($("#content1").is(':visible')) && ($("#content3").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header32").css({display: "none"});
          $("#header16").css({display: "block"});
          $("#header8").css({display: "block"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          alert("Content 1 and 3 are visible");
          $("#content1").hide();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").show();
          $("#content5").show();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
      }
      if(total_players > 16)
      {
        if(($("#content1").is(':visible')) && ($("#content4").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header32").css({display: "none"});
          $("#header16").css({display: "block"});
          $("#header8").css({display: "block"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          alert("Content 1 and 4 are visible");
          $("#content1").hide();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").show();
          $("#content5").show();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
      }
      if(total_players > 8)
      {
        if(($("#content1").is(':visible')) && ($("#content5").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header32").css({display: "none"});
          $("#header16").css({display: "block"});
          $("#header8").css({display: "block"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          alert("Content 1 and 5 are visible");
          $("#content1").hide();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").show();
          $("#content5").show();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
      }
      if(total_players > 4)
      {
        if(($("#content1").is(':visible')) && ($("#content6").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header32").css({display: "none"});
          $("#header16").css({display: "block"});
          $("#header8").css({display: "block"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          alert("Content 1 and 6 are visible");
          $("#content1").hide();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").show();
          $("#content5").show();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
      }
      if(($("#content1").is(':visible')) && ($("#content7").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "block"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});
    
        alert("Content 1 and 7 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").show();
        $("#content5").show();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      */
      if(($("#content3").is(':visible')) && ($("#content4").is(':visible')))
      {
        alert("Content 3 and 4 are visible");
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "block"});
        $("#header16").css({display: "block"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});
    
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").show();
        $("#content5").show();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content4").is(':visible')) && ($("#content5").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "block"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});
    
        alert("Content 4 and 5 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").show();
        $("#content6").show();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content5").is(':visible')) && ($("#content6").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "block"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});
    
        alert("Content 5 and 6 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").show();
        $("#content6").show();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content6").is(':visible')) && ($("#content7").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "block"});
        $("#header1").css({display: "none"});
    
        alert("Content 6 and 7 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").show();
        $("#content7").show();
        $("#content8").hide();
      }
      else if(($("#content7").is(':visible')) && ($("#content8").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "block"});
        $("#header1").css({display: "block"});
    
        alert("Content 7 and 8 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").show();
        $("#content8").show();
      }
    });

    $("#backBtn").click(function()
    {
      if(total_players > 64)
      {
        if(($("#content1").is(':visible') || $("#content2").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header64").css({display: "block"});
          $("#header32").css({display: "block"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          alert("Content 1 and 2 are visible");
          $("#content1").show();
          $("#content2").hide();
          $("#content3").show();
          $("#content4").hide();
          $("#content5").hide();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
      }
      if(total_players > 32)
      {
        if(($("#content1").is(':visible') || $("#content3").is(':visible')) && ($("#content4").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header64").css({display: "block"});
          $("#header32").css({display: "block"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          alert("Content 1 and 3 are visible");
          $("#content1").show();
          $("#content2").hide();
          $("#content3").show();
          $("#content4").hide();
          $("#content5").hide();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
      }
      if(total_players > 16)
      {
        if(($("#content1").is(':visible') || $("#content4").is(':visible')) && ($("#content5").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header64").css({display: "block"});
          $("#header32").css({display: "block"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          alert("Content 1 and 4 are visible");
          $("#content1").show();
          $("#content2").hide();
          $("#content3").show();
          $("#content4").hide();
          $("#content5").hide();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
      }
      if(total_players > 8)
      {
      // good for 64 players
        if(($("#content1").is(':visible') || $("#content5").is(':visible')) && ($("#content6").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header64").css({display: "block"});
          $("#header32").css({display: "block"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          alert("Content 1 and 5 are visible");
          $("#content1").show();
          $("#content2").hide();
          $("#content3").show();
          $("#content4").hide();
          $("#content5").hide();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
      }
      if(total_players > 4)
      {
        if(($("#content1").is(':visible') || $("#content6").is(':visible')) && ($("#content7").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header64").css({display: "block"});
          $("#header32").css({display: "block"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          alert("Content 1 and 6 are visible");
          $("#content1").show();
          $("#content2").hide();
          $("#content3").show();
          $("#content4").hide();
          $("#content5").hide();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
      }
      else if(($("#content2").is(':visible')) && ($("#content3").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "block"});
        $("#header16").css({display: "block"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});


        alert("Content 2 and 3 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").show();
        $("#content4").show();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content3").is(':visible')) && ($("#content4").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "block"});
        $("#header16").css({display: "block"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});


        alert("Content 3 and 4 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").show();
        $("#content4").show();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content4").is(':visible')) && ($("#content5").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "block"});
        $("#header16").css({display: "block"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});


        alert("Content 4 and 5 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").show();
        $("#content4").show();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content5").is(':visible')) && ($("#content6").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "block"});
        $("#header8").css({display: "block"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});


        alert("Content 5 and 6 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").show();
        $("#content5").show();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content6").is(':visible')) && ($("#content7").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "block"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});

        alert("Content 6 and 7 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").show();
        $("#content6").show();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content7").is(':visible')) && ($("#content8").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "block"});
        $("#header1").css({display: "none"});

        alert("Content 7 and 8 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").show();
        $("#content7").show();
        $("#content8").hide();
      }
    });
  
/*
  if(total_players == 32)
  {
    $("#nextBtn").click(function()
    {
        if(($("#content1").is(':visible')) && ($("#content4").is(':visible')))
        {
          //$("#header128").css({display: "none"});
          $("#header32").css({display: "none"});
          $("#header16").css({display: "block"});
          $("#header8").css({display: "block"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          //alert("Content 1 and 4 are visible");
          $("#content1").hide();
          $("#content4").show();
          $("#content5").show();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
        else if(($("#content4").is(':visible')) && ($("#content5").is(':visible')))
        {
          $("#header32").css({display: "none"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "block"});
          $("#header4").css({display: "block"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          //alert("Content 4 and 5 are visible");
          $("#content1").hide();
          $("#content4").hide();
          $("#content5").show();
          $("#content6").show();
          $("#content7").hide();
          $("#content8").hide();
        }
        else if(($("#content5").is(':visible')) && ($("#content6").is(':visible')))
        {
          $("#header32").css({display: "none"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "block"});
          $("#header2").css({display: "block"});
          $("#header1").css({display: "none"});
      
          //alert("Content 5 and 6 are visible");
          $("#content1").hide();
          $("#content4").hide();
          $("#content5").hide();
          $("#content6").show();
          $("#content7").show();
          $("#content8").hide();
        }
        else if(($("#content6").is(':visible')) && ($("#content7").is(':visible')))
        {
          $("#header32").css({display: "none"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "block"});
          $("#header1").css({display: "block"});
      
          //alert("Content 6 and 7 are visible");
          $("#content1").hide();
          $("#content4").hide();
          $("#content5").hide();
          $("#content6").hide();
          $("#content7").show();
          $("#content8").show();
        }

    });
    $("#backBtn").click(function()
    {
      // good for 32 players
        if(($("#content1").is(':visible') || $("#content4").is(':visible')) && ($("#content5").is(':visible')))
        {
          $("#header32").css({display: "block"});
          $("#header16").css({display: "block"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          //alert("Content 1 and 4 are visible");
          $("#content1").show();
          $("#content4").show();
          $("#content5").hide();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
        else if(($("#content5").is(':visible')) && ($("#content6").is(':visible')))
        {
          $("#header32").css({display: "none"});
          $("#header16").css({display: "block"});
          $("#header8").css({display: "block"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});


          //alert("Content 5 and 6 are visible");
          $("#content1").hide();
          $("#content4").show();
          $("#content5").show();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
        else if(($("#content6").is(':visible')) && ($("#content7").is(':visible')))
        {
          $("#header32").css({display: "none"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "block"});
          $("#header4").css({display: "block"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});

          //alert("Content 6 and 7 are visible");
          $("#content1").hide();
          $("#content4").hide();
          $("#content5").show();
          $("#content6").show();
          $("#content7").hide();
          $("#content8").hide();
        }
        else if(($("#content7").is(':visible')) && ($("#content8").is(':visible')))
        {
          $("#header32").css({display: "none"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "block"});
          $("#header2").css({display: "block"});
          $("#header1").css({display: "none"});

          //alert("Content 7 and 8 are visible");
          $("#content1").hide();
          $("#content4").hide();
          $("#content5").hide();
          $("#content6").show();
          $("#content7").show();
          $("#content8").hide();
        }
    });
  }
*/
</script>
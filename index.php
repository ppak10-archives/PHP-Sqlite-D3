<!-- D3 Trades Navigator -->

<!-- Initial PHP scripts -->
<?php
   class SQLiteDB extends SQLite3
   {
     function __construct()
      {
        $this->open('crypto.db');// open database file
      }
   }

   $db = new SQLiteDB(); // Call database file
   if(!$db){ // If no database file exists
      echo $db->lastErrorMsg();
   } else { // If databse file is located
      $trades=$db->prepare('SELECT*FROM test_trades WHERE pair1=:pair1 AND pair2=:pair2');

      // Database values
      $counts = $db->query('SELECT COUNT(*) as tradeCount FROM test_trades');
      $count = $counts->fetchArray();
      $tradeCount = $count['tradeCount'];

      $pair1Data = array();
      $pair2Data = array();
      $currencies = array();
      $pair1s = $db->query('SELECT DISTINCT pair1 FROM test_trades');
      $pair2s = $db->query('SELECT DISTINCT pair2 FROM test_trades');

      if ($pair1 or $pair2){
          $plotData = [];
          while ($row = $result->fetchArray()){

            // Plot Data
            $plotData[] = array("ts" => $row['ts'],"price" => $row['price']);

          }
      }
      while ($pair1value = $pair1s->fetchArray()){
        array_push($pair1Data, $pair1value[0]);
      }
      while ($pair2value = $pair2s->fetchArray()){
        array_push($pair2Data, $pair2value[0]);
      }

      $currenciesData = $pair1Data + $pair2Data;
      $rowLimit = 100;
      $timeGranularity = 60;
   }

   // Handle Post requests
   if (isset($_POST['submit']))
   {
     $pair1 = $_POST['pair1'];
     $pair2 = $_POST['pair2'];
     $chartType = $_POST['chartType'];
     $rowLimit = $_POST['rowLimit'];
     $timeGranularity = $_POST['timeGranularity'];
   }
?>

<html>
<!-- Content Delivery Network Scripts -->
  <head>
  <!-- JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>

  <!-- Bootstrap -->
    <!-- Complete CSS-->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <!-- Complete JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
    <!-- Complete JavaScript Bundle -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.bundle.min.js" integrity="sha384-CS0nxkpPy+xUkNGhObAISrkg/xjb3USVCwy+0/NMzd5VxgY4CMCyTkItmy5n0voC" crossorigin="anonymous"></script>



  <!-- D3 -->
    <script src="https://d3js.org/d3.v5.min.js"></script>

    <title>
      <?php
        if($pair1 && $pair2 && $chartType){
          echo $pair1."-".$pair2.": ".ucfirst($chartType)." Chart";
        } else {
          echo "Trades Database";
        }
       ?>
    </title>
  </head>

<!-- HTML Page Content -->
  <body>
    <div class="row container m-2">
      <h3>Trades Database</h3>
      <h6 class="m-2 text-muted">
        <?php  echo $tradeCount;?> Total Trades,
      </h6>
      <h6 class="m-2 text-muted">
        <?php  echo count($currenciesData);?> Total Currencies,
      </h6>
    </div>

  <!-- Set Chart Parameter -->
    <form class="col" action="index.php" method="post">
      <div class="row container m-2">
      <!-- Exchange Pairs - Text Input -->
        <div class="col-3 container m-2">
          <h4>Exchange Pairs</h4>
          <select name="pair1" placeholder="Pair 1">
            <?php
              if($pair1){
                echo "<option value='".$pair1."'selected>".$pair1."</option>";
              } else {
                echo "<option value='' disabled selected>Pair 1</option>";
              }
              foreach ($pair1Data as $pair1Select){
                echo "<option value='".$pair1Select."'>".$pair1Select."</option>";
              }
             ?>
          </select>
          -<select name="pair2" placeholder="Pair 2">
            <?php
              if($pair2){
                echo "<option value='".$pair2."'selected>".$pair2."</option>";
              } else {
                echo "<option value='' disabled selected>Pair 2</option>";
              }
              foreach ($pair2Data as $pair2Select){
                echo "<option value='".$pair2Select."'>".$pair2Select."</option>";
              }
             ?>
          </select>
        </div>

      <!-- Chart Types - Radio Buttons -->
        <h4 class="mt-4">Chart Types:</h4>
        <div class="col btn-group btn-group-toggle mt-4 mb-4" data-toggle="buttons">

        <!-- Price Chart Button -->
          <label
            <?php
              echo "class='btn btn-secondary";
              if($chartType=="price"){
                echo "active'>";
              } else {
                echo "'>";
              } ?>
            <input class="btn btn-outline" type="radio" name="chartType" value="price"
              <?php
                if ((isset($chartType)&& $chartType=="price")or!isset($chartType)){
                  echo "checked";
                };
              ?>
            > Price
          </label>

        <!-- Sentiment Chart Button -->
          <label <?php
            echo "class='btn btn-secondary";
            if($chartType=="sentiment"){
              echo "active'>";
            } else {
              echo "'>";
            } ?>
            <input
              class="btn btn-outline"
              type="radio"
              name="chartType"
              value="sentiment"
              <?php
                if(isset($chartType)&&$chartType=="sentiment"){
                  echo"checked";
                }?>> Sentiment
          </label>

        <!-- Volume Chart Button -->
          <label <?php
            echo "class='btn btn-secondary";
            if($chartType=="volume"){
              echo "active'>";
            } else {
              echo "'>";
            } ?>
            <input
              class="btn btn-outline"
              type="radio"
              name="chartType"
              value="volume"
              <?php
                if(isset($chartType)&& $chartType=="volume"){
                  echo"checked";
                }?>> Volume
          </label>
        </div>
      </div>

    <!-- Preferences Row -->
      <div class="container row m-2">
        Row Limit:
        <input
          class="mr-4"
          type="text"
          name="rowLimit"
          value=<?php echo $rowLimit; ?> />
        Time Granularity:
        <input
          type="text"
          name="timeGranularity"
          value=<?php echo $timeGranularity; ?> /> Seconds
      </div>

    <!-- Submit Button -->
      <div class="container row m-2">
        <input class="btn btn-outline-primary col"type="submit" name="submit">
      </div>
    </form>

  <!-- PHP framework for Chart Parameters-->
    <?php
      $trades->bindValue(':pair1',$pair1);
      $trades->bindValue(':pair2',$pair2);
      $result = $trades->execute();

      if ($pair1 && $pair2){
        $plotData = [];
        $oldTimestamp = -1;
        $cache = 0;
        $cacheRows = 0;
        while ($row = $result->fetchArray()){
          if ($oldTimestamp == -1){ // set Initial Timestamp
            $oldTimestamp = $row['ts'];
          }

          if (floatval($row['ts'])<(floatval($oldTimestamp)-$timeGranularity)){
            // record plot point
            $average = $cache/$cacheRows;
            $plotData[] = array("x" => $row['ts'],"y" => $average);

            // reset
            $cache = 0;
            $cacheRows = 0;
            $oldTimestamp = $row['ts'];
          }

          // Chart Types
          if ($chartType == "price"){
            $cache += floatval($row['price']);
          } elseif ($chartType == "sentiment") {
            $cache += floatval($row['isbuy']);
          } elseif ($chartType == "volume") {
            $cache += floatval($row['volume']);
          }
          $cacheRows += 1;
        }
      }
    ?>

  <!-- D3 Charts -->
    <svg class="line-chart col"></svg>
    <script type="text/javascript">

      /**
        * Loading data from API when DOM Content has been Loaded'.
        */

      document.addEventListener("DOMContentLoaded", function(event){
        drawChart(<?php echo json_encode($plotData); ?>);
      });

      /**
        * Creates a chart using D3
        * @param {object} data Object containing historical data of BPI
        */
      function drawChart(data){
        var svgWidth = 1080, svgHeight = 720;
        var margin = { top: 20, right: 20, bottom: 30, left: 50};
        var width = svgWidth - margin.left - margin.right;
        var height = svgHeight -margin.top - margin.bottom;

        var svg = d3.select("svg")
          .attr("width", svgWidth)
          .attr("height", svgHeight);

        var g = svg.append("g")
          .attr("transform", "translate(" + margin.left + "," +margin.top +")");

        var x = d3.scaleTime()
          .rangeRound([0,width]);

        var y = d3.scaleLinear()
          .rangeRound([height, 0]);

        var line = d3.line()
          .x(function(d) { return x(parseFloat(d.x))})
          .y(function(d) { return y(parseFloat(d.y))})
          x.domain(d3.extent(data, function(d) {return parseFloat(d.x)}));
          y.domain(d3.extent(data, function(d) {return parseFloat(d.y)}));

        g.append("g")
          .attr("transform", "translate(0,"+height+")")
          .call(d3.axisBottom(x))
          .select(".domain")
          .remove();

        g.append("g")
          .call(d3.axisLeft(y))
          .append("text")
          .attr("transform", "rotate(-90)")
          .attr("y", 6)
          .attr("dy", "0.71em")
          .attr("text-anchor", "end")
          .text("Price ($)");

        g.append("path")
          .datum(data)
          .attr("fill", "none")
          .attr("stroke", "steelblue")
          .attr("stroke-linejoin", "round")
          .attr("stroke-linecap", "round")
          .attr("stroke-width", 1.5)
          .attr("d", line);
      }
    </script>

  <!-- Trades PHP Table -->
    <?php
      if ($pair1 or $pair2){
        echo "<div class='row container'>";
        echo "<h2>".$pair1."-".$pair2."</h2>";
        echo "<h6 class='m-2 text-muted'>".ucfirst($chartType)." chart,</h6>";
        echo "<h6 class='m-2 text-muted'>".count($plotData)." plot points,</h6>";
        echo "<h6 class='m-2 text-muted'>Viewing ".$rowLimit." trades per page,</h6>";
        echo "<h6 class='m-2 text-muted'>Points are ".$chartType." average over ".$timeGranularity." seconds</h6>";
        echo "</div>";
        echo "<table class='table table-dark table-striped table-bordered table-sm table-hover'>";
        echo "<thead class='thead-light'>";
        echo "<tr>";
        echo "<th scope='col'>Exchange</th>";
        echo "<th scope='col'>Pair 1</th>";
        echo "<th scope='col'>Pair 2</th>";
        echo "<th scope='col'>Price</th>";
        echo "<th scope='col'>Volume</th>";
        echo "<th scope='col'>Buy</th>";
        echo "<th scope='col'>Limit</th>";
        echo "<th scope='col'>Exchange Timestamp</th>";
        echo "<th scope='col'>Server Timestamp</th>";
        echo "</tr>";
        echo "</thead>";
          $counter = 0;
          $plotData = [];
          echo "<tbody>";
          while ($row = $result->fetchArray()){

            // Output to Trades Table
            if($counter < $rowLimit){
              echo "<tr>";
              echo "<td>". $row['exchange'] ."</td>";
              echo "<td>". $row['pair1'] ."</td>";
              echo "<td>". $row['pair2'] ."</td>";
              echo "<td>". $row['price'] ."</td>";
              echo "<td>". $row['volume'] ."</td>";
              echo "<td>". $row['isbuy'] ."</td>";
              echo "<td>". $row['islimit'] ."</td>";
              echo "<td>". $row['exchangets'] ."</td>";
              echo "<td>". $row['ts'] ."</td>";
              echo "</tr>";
            }

            // Plot Data
            $plotData[] = array("ts" => $row['ts'],"price" => $row['price']);
            $counter += 1;
          }
        echo "</tbody>";
        echo "</table>";
      }
    ?>

  </body>
</html>

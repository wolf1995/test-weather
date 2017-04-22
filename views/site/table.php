
<style media="screen">
  table{
    text-align: center;
  }
  th{
    padding-right: 10px;
  }
</style>
<table>
  <tr>
    <th>
      <?php if (isset($city['0'])): ?>
        <?= $city['0']->cityName;  ?>
      <?php else: ?>
        <?= $city->cityName ?>
      <?php endif; ?>
    </th>
    <th>maxTemp</th>
    <th>minTemp</th>
    <th>Amplitude</th>
  </tr>
  <?php if (isset($arrWeatherNewCity)): ?>
    <?php foreach ($arrWeatherNewCity as $weather): ?>
        <tr>
          <td><?= Yii::$app->formatter->asDate($weather['date'])?></td>
          <td><?= $weather['maxTemp'] ?></td>
          <td><?= $weather['minTemp'] ?></td>
          <td class='ampl'><?= abs($weather['maxTemp']) - abs($weather['minTemp']) ?></td>
        </tr>
    <?php endforeach ?>
  <?php else: ?>
    <?php foreach ($weathers as $weather): ?>
        <tr>
          <td><?= Yii::$app->formatter->asDate($weather['date'])?></td>
          <td><?= $weather['maxTemp'] ?></td>
          <td><?= $weather['minTemp'] ?></td>
          <td class='ampl'><?= abs($weather['maxTemp'])-abs($weather['minTemp']) ?></td>
        </tr>
    <?php endforeach ?>
  <?php endif; ?>
</table>

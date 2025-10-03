<h1>Notre Équipe</h1>
<h2>Nos Moniteurs</h2>
<div class="team-container">
  <?php foreach ($teamMembers as $member): ?>
    <div class="team-member">
      <img src="<?= htmlspecialchars($member['photo']) ?>" alt="<?= htmlspecialchars($member['nommoniteur']) ?>">
      <h2><?= htmlspecialchars($member['nommoniteur']) ?></h2>
      <p><?= htmlspecialchars($member['prenommoniteur']) ?></p>
    </div>
  <?php endforeach; ?>
</div>
<h2>Nos Véhicules</h2>
<div class="vehicle-container">
  <?php foreach ($vehicles as $vehicle): ?>
    <div class="vehicle">
      <img src="<?= htmlspecialchars($vehicle['image']) ?>" alt="<?= htmlspecialchars($vehicle['name']) ?>">
      <h2><?= htmlspecialchars($vehicle['name']) ?></h2>
    </div>
  <?php endforeach; ?>
</div>
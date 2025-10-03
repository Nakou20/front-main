  <h1>Notre Équipe</h1>

  <!-- Section Moniteurs -->
  <section>
    <h2>Nos Moniteurs</h2>
    <div class="grid grid-cols-3 gap-6">
      <?php foreach($moniteurs as $moniteur): ?>
        <div class="card">
          <img src="<?= $moniteur->photo ?? '/images/default-profile.png' ?>" 
               alt="Photo de <?= $moniteur->prenom ?> <?= $moniteur->nom ?>" />
          <h3><?= $moniteur->prenom ?> <?= $moniteur->nom ?></h3>
        </div>
    </div>
  </section>

  <!-- Section Véhicules -->
  <section>
    <h2>Nos Véhicules</h2>
    <div class="grid grid-cols-3 gap-6">
      <?php foreach($vehicules as $vehicule): ?>
        <div class="card">
          <img src="/images/vehicules/generic-car.png" 
               alt="Véhicule <?= $vehicule->designation ?>" />
          <h3><?= $vehicule->designation ?></h3>
          <p>Transmission : <?= ucfirst($vehicule->transmission) ?></p>
        </div>
    </div>
  </section>


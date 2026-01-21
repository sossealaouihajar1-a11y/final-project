<template>
  <div class="vintage-logo">
    <span class="text">Vintage</span>

    <div class="icon">
      <div class="line"></div>
      <span class="symbol">✦</span>
      <div class="line"></div>
    </div>

    <span class="text">Shop</span>
  </div>
</template>

<style scoped>
.vintage-logo {
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Cinzel', serif;
  font-size: 48px;
  letter-spacing: 6px;
  color: #2b2b2b;
}

.text {
  text-transform: uppercase;
}

.icon {
  display: flex;
  align-items: center;
  margin: 0 20px;
}

.symbol {
  font-size: 24px;
  margin: 0 10px;
}

.line {
  width: 50px;
  height: 2px;
  background-color: #2b2b2b;
}
</style>

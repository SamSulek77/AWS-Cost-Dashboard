<div x-data x-init="$nextTick(() => {
    const canvas = $refs.pieCanvas;
    canvas.classList.add('spin-once');
    setTimeout(() => canvas.classList.remove('spin-once'), 2000);
})">
    <canvas x-ref="pieCanvas" id="aws-cost-pie-chart"></canvas>
</div>

<style>
@keyframes spinOnce {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.spin-once {
    animation: spinOnce 2s ease-in-out;
}
</style>

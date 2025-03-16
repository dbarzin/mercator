import {Chart, DoughnutController, ArcElement} from "chart.js";
import {
    toPercentage,
    toDimension,
    toRadians,
    addRoundedRectPath,
    PI,
    TAU,
    HALF_PI,
    _angleBetween
} from "chart.js/helpers";

function getRatioAndOffset( rotation, circumference, cutout, needleOpts ) {
  let ratioX = 1;
  let ratioY = 1;
  let offsetX = 0;
  let offsetY = 0;
  // If the chart's circumference isn't a full circle, calculate size as a ratio of the width/height of the arc
  if ( circumference < TAU ) {
    const startAngle = rotation;
    const halfAngle = startAngle + circumference / 2;
    const endAngle = startAngle + circumference;
    const startX = Math.cos( startAngle );
    const startY = Math.sin( startAngle );
    const endX = Math.cos( endAngle );
    const endY = Math.sin( endAngle );
    // include center for needle
    const {
      radiusPercentage,
      widthPercentage,
      lengthPercentage,
    } = needleOpts;
    const needleWidth = Math.max( radiusPercentage / 100, widthPercentage / 2 / 100 );
    const calcMax = ( angle, a, b ) => _angleBetween( angle, startAngle, endAngle ) ? Math.max(  1,  lengthPercentage / 100 ) : Math.max( a, a * cutout, b, b * cutout,  needleWidth );
    const calcMin = ( angle, a, b ) => _angleBetween( angle, startAngle, endAngle ) ? Math.min( -1, -lengthPercentage / 100 ) : Math.min( a, a * cutout, b, b * cutout, -needleWidth );
    const maxX = calcMax( 0, startX, endX );
    const maxY = calcMax( HALF_PI, startY, endY );
    const minX = calcMin( PI, startX, endX );
    const minY = calcMin( PI + HALF_PI, startY, endY );

    ratioX = ( maxX - minX ) / 2;
    ratioY = ( maxY - minY ) / 2;
    offsetX = -( maxX + minX ) / 2;
    offsetY = -( maxY + minY ) / 2;
  }
  return { ratioX, ratioY, offsetX, offsetY };
}

export default class GaugeController extends DoughnutController {

  parse( start, count ) {
    super.parse( start, count);
    const dataset = this.getDataset();
    const meta = this._cachedMeta;
    meta.minValue = dataset.minValue || 0;
    meta.value = dataset.value;
  }

  /**
	 * @param {string} mode
	 */
   update(mode) {
    const me = this;
    const chart = me.chart;
    const {chartArea} = chart;
    const meta = me._cachedMeta;
    const arcs = meta.data;
    const spacing = me.getMaxBorderWidth() + me.getMaxOffset(arcs);
    const maxSize = Math.max((Math.min(chartArea.width, chartArea.height) - spacing) / 2, 0);
    const cutout = Math.min(toPercentage(me.options.cutout, maxSize), 1);
    const chartWeight = me._getRingWeight(me.index);

    // Compute the maximal rotation & circumference limits.
    // If we only consider our dataset, this can cause problems when two datasets
    // are both less than a circle with different rotations (starting angles)
    const {circumference, rotation} = me._getRotationExtents();
    const {ratioX, ratioY, offsetX, offsetY} = getRatioAndOffset( rotation, circumference, cutout, this.options.needle );
    const maxWidth = (chartArea.width - spacing) / ratioX;
    const maxHeight = (chartArea.height - spacing) / ratioY;
    const maxRadius = Math.max(Math.min(maxWidth, maxHeight) / 2, 0);
    const outerRadius = toDimension(me.options.radius, maxRadius);
    const innerRadius = Math.max(outerRadius * cutout, 0);
    const radiusLength = (outerRadius - innerRadius) / me._getVisibleDatasetWeightTotal();
    me.offsetX = offsetX * outerRadius;
    me.offsetY = offsetY * outerRadius;

    meta.total = me.calculateTotal();

    me.outerRadius = outerRadius - radiusLength * me._getRingWeightOffset(me.index);
    me.innerRadius = Math.max(me.outerRadius - radiusLength * chartWeight, 0);

    me.updateElements(arcs, 0, arcs.length, mode);
  }

  calculateTotal() {
    const meta = this._cachedMeta;
    const metaData = meta.data;
    let total = 0;

    // get Min/Max
    let valueMin = meta.minValue;
    let valueMax = meta.minValue;
    let i;
    for ( i = 0; i < metaData.length; i++ ) {
      const value = meta._parsed[ i ];
      if ( value !== null && !isNaN( value ) && this.chart.getDataVisibility( i ) ) {
        if ( value < valueMin )
          valueMin = value;
        if ( value > valueMax )
          valueMax = value;
      }
    }

    meta.minValue = valueMin;

    // calc total
    if (  valueMin !== null && !isNaN( valueMin )
       && valueMax !== null && !isNaN( valueMax )
       ) {
      total = Math.abs( valueMax - valueMin );
    }
    return total;
  }

  updateElements(arcs, start, count, mode) {
    const me = this;
    const reset = mode === 'reset';
    const chart = me.chart;
    const chartArea = chart.chartArea;
    const opts = chart.options;
    const animationOpts = opts.animation;
    const centerX = (chartArea.left + chartArea.right) / 2;
    const centerY = (chartArea.top + chartArea.bottom) / 2;
    const animateScale = reset && animationOpts.animateScale;
    const innerRadius = animateScale ? 0 : me.innerRadius;
    const outerRadius = animateScale ? 0 : me.outerRadius;
    const firstOpts = me.resolveDataElementOptions(start, mode);
    const sharedOptions = me.getSharedOptions(firstOpts);
    const includeOptions = me.includeOptions(mode, sharedOptions);
    const rotation = me._getRotation();
    var startAngle = rotation;

    var i;
    if (start > 0) startAngle = me._circumference(start, reset) + rotation;

    for (i = start; i < start + count; ++i) {
      var endAngle = me._circumference(i, reset) + rotation;
      const arc = arcs[i];
      const properties = {
        x: centerX + me.offsetX,
        y: centerY + me.offsetY,
        startAngle,
        endAngle,
        circumference: endAngle - startAngle,
        outerRadius,
        innerRadius
      };
      if (includeOptions) {
        properties.options = sharedOptions || me.resolveDataElementOptions(i, mode);
      }
      startAngle = endAngle;

      me.updateElement(arc, i, properties, mode);
    }
    me.updateSharedOptions(sharedOptions, mode, firstOpts);
  }

  calculateCircumference(value) {
    const total = this._cachedMeta.total;
    const minValue = this._cachedMeta.minValue;
    if ( total > 0 && !isNaN( value ) && !isNaN( minValue ) ) {
        const circumference = this._getCircumference();
        const minCircumference = minValue * circumference / TAU;
        return TAU * ( Math.abs( value - minCircumference ) / total );
    }
    return 0;
  }

  getTranslation( chart ) {
    const { chartArea } = chart;
    const centerX = ( chartArea.left + chartArea.right ) / 2;
    const centerY = ( chartArea.top + chartArea.bottom ) / 2;
    const dx = ( centerX + this.offsetX || 0 );
    const dy = ( centerY + this.offsetY || 0 );
    return { dx, dy };
  }

  drawNeedle() {
    const {
      ctx,
      chartArea,
    } = this.chart;
    const {
      innerRadius,
      outerRadius,
    } = this;
    const {
      radiusPercentage,
      widthPercentage,
      lengthPercentage,
      color,
    } = this.options.needle;

    const width = chartArea.right - chartArea.left;
    const needleRadius = ( radiusPercentage / 100 ) * width;
    const needleWidth = ( widthPercentage / 100 ) * width;
    const needleLength = ( lengthPercentage / 100 ) * ( outerRadius - innerRadius ) + innerRadius;

    // center
    const { dx, dy } = this.getTranslation( this.chart );

    // interpolate
    const meta = this._cachedMeta;
    const circumference = this._getCircumference();
    const rotation = this._getRotation();
    var angle = this.calculateCircumference( meta.value * circumference / TAU ) + rotation;

// draw
    ctx.save();
    ctx.translate( dx, dy );
    ctx.rotate( angle );
    ctx.fillStyle = color;

    // draw circle
    ctx.beginPath();
    ctx.ellipse( 0, 0, needleRadius, needleRadius, 0, 0, 2 * Math.PI );
    ctx.fill();

    // draw needle
    ctx.beginPath();
    ctx.moveTo( 0, needleWidth / 2 );
    ctx.lineTo( needleLength, 0 );
    ctx.lineTo( 0, -needleWidth / 2 );
    ctx.fill();

    ctx.restore();
  }

  drawValueLabel() { // eslint-disable-line no-unused-vars
    if (!this.options.valueLabel.display) {
      return;
    }
    const {
      ctx,
      config,
      chartArea,
    } = this.chart;
    const {
      defaultFontFamily,
    } = config.options;
    const dataset = config.data.datasets[this.index];
    const {
      formatter,
      fontSize,
      color,
      backgroundColor,
      borderRadius,
      padding,
      bottomMarginPercentage,
    } = this.options.valueLabel;

    const width = chartArea.right - chartArea.left;
    const bottomMargin = (bottomMarginPercentage / 100) * width;

    const fmt = (value) => { return value.toFixed(0); };
    const valueText = fmt(dataset.value).toString()+"%"; // TODO: fixme
    ctx.textBaseline = 'middle';
    ctx.textAlign = 'center';
    if (fontSize) {
      ctx.font = `${fontSize}px ${defaultFontFamily}`;
    }

    // const { width: textWidth, actualBoundingBoxAscent, actualBoundingBoxDescent } = ctx.measureText(valueText);
    // const textHeight = actualBoundingBoxAscent + actualBoundingBoxDescent;

    const { width: textWidth } = ctx.measureText(valueText);
    // approximate height until browsers support advanced TextMetrics
    const textHeight = Math.max(ctx.measureText('m').width, ctx.measureText('\uFF37').width);

    const x = (padding.left + textWidth / 2);
    const y = (padding.top + textHeight / 2);
    const w = (padding.left + textWidth + padding.right);
    const h = (padding.top + textHeight + padding.bottom);

    // center
    let { dx, dy } = this.getTranslation(this.chart);
    // add rotation
    const rotation = toRadians( this.chart.options.rotation ) % (Math.PI * 2.0);
    dx += bottomMargin * Math.cos(rotation + Math.PI / 2);
    dy += bottomMargin * Math.sin(rotation + Math.PI / 2);

    // draw
    ctx.save();
    ctx.translate(dx+fontSize, dy);
    // draw background
/*
    ctx.beginPath();
    addRoundedRectPath(ctx, {
      x: -w,
      y: -h,
      w: w,
      h: h,
      radius: borderRadius,
    });
    ctx.fillStyle = backgroundColor;
    ctx.fill();
*/
    ctx.fillStyle = backgroundColor;
    ctx.fillRect(-w, -h+4, w, h-4); // TODO: fixme

    // draw value text
    ctx.fillStyle = color || config.options.defaultFontColor;
    ctx.translate(-w/2 , -h + (fontSize/2));
    ctx.fillText(valueText, 0, textHeight);

    ctx.restore();

  }

  draw() {
    super.draw();
    this.drawNeedle();
    this.drawValueLabel();

  }

}

GaugeController.overrides = {
  needle: {
    // Needle circle radius as the percentage of the chart area width
    radiusPercentage: 2,
    // Needle width as the percentage of the chart area width
    widthPercentage: 3.2,
    // Needle length as the percentage of the interval between inner radius (0%) and outer radius (100%) of the arc
    lengthPercentage: 80,
    // The color of the needle
    color: 'rgba(0, 0, 0, 1)',
  },
  valueLabel: {
    fontSize: '12',
    display: true,
    formatter: null,
    color: '#000000',
    backgroundColor: '#ffff00',
    borderRadius: 5,
    padding: {
      top: 5,
      right: 5,
      bottom: 5,
      left: 5,
    },
    bottomMarginPercentage: 5,
  },
  // The percentage of the chart that we cut out of the middle.
  cutout: '50%',
  // The rotation of the chart, where the first data arc begins.
  rotation: -90,
  // The total circumference of the chart.
  circumference: 180,
  plugins: {
    legend: {
      display: false,
    },
    tooltip: {
      enabled: false,
    },
  }
};

// Enregistrer le contrôleur pour Chart.js v4
GaugeController.id = 'gauge';
GaugeController.version = '0.0.0';

Chart.register(GaugeController, ArcElement);

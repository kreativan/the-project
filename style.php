<style>
#wpbody {
  padding-right: 20px;
}

/*  Utility
=========================================================== */
 
.margin {
  margin: 20px 0;
}

.margin-remove {
  margin: 0 !important;
}

.padding {
  padding: 10px;
}

/*  Grid
=========================================================== */

.grid {
  display: grid;
  grid-auto-columns: auto;
}

.grid-gap {
  grid-gap: 20px;
}

.grid.grid-6 {grid-template-columns: repeat(6, 1fr);}
.grid.grid-5 {grid-template-columns: repeat(5, 1fr);}
.grid.grid-4 {grid-template-columns: repeat(4, 1fr);}
.grid.grid-3 {grid-template-columns: repeat(3, 1fr);}
.grid.grid-2 {grid-template-columns: 1fr 1fr;}
.grid.grid-1 {grid-template-columns: 1fr;}

@media(max-width: 960px) {
  .grid {
    grid-template-columns: 1fr !important;
  }
}

/*  Flex
=========================================================== */

.flex {display: flex;}

/* Horizontal */
.flex-left { justify-content: flex-start; }
.flex-center { justify-content: center; }
.flex-right { justify-content: flex-end; }
.flex-between { justify-content: space-between; }
.flex-around { justify-content: space-around; }

/* Vertical */
.flex-stretch { align-items: stretch; }
.flex-top { align-items: flex-start; }
.flex-middle { align-items: center; }
.flex-bottom { align-items: flex-end; }

/* wrap */
.flex-nowrap { flex-wrap: nowrap; }
.flex-wrap { flex-wrap: wrap; }
.flex-wrap-reverse { flex-wrap: wrap-reverse; }

/* expand */
.flex-expand {flex: 1;}

/* 1fr, 2fr, 3fr */
.flex-1 {flex: 1;}
.flex-2 {flex: 2;}
.flex-3 {flex: 3;}

/* auto */
.flex-auto { flex: auto; }

/*  Table
=========================================================== */

table {
  width:100%;
  border-collapse:collapse;
}
table td {
  padding: 7px 5px;
}
table th {
  text-align: left;
  background: #f8f8f8;
  color: #333;
  padding: 5px;
}
table tr:nth-child(even) td {
  background: #f8f8f8;
}

/*  panel
=========================================================== */
.panel {
  display: block;
  box-sizing: border-box;
  position: relative;
  background: white;
  border-radius: 3px;
  overflow: hidden;
}

</style>